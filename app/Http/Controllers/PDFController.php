<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\InvoiceService;
use App\Services\OpenRouterService;
use PDF;

class PDFController extends Controller
{

    public function publish(Request $request, $id)
    {
        $InvoiceService = new InvoiceService();

        $invoice = $InvoiceService->getById($id);

        if (!$invoice) {
            return abort(404, 'El recurso no fue encontrado.');
        }

        $publishOptions = [
            'title' => $request->input('title'),
            'currency' => $request->input('currency', 'MXN'),
            'exchange_rate' => $request->input('exchange_rate', 1),
            'language' => $request->input('language', 'es'),
            'date' => $request->input('date', date('Y-m-d')),
        ];

        $isEnglish = $publishOptions['language'] !== 'es';
        $exchangeRate = $publishOptions['exchange_rate'] ?: 1;
        $currencyCode = $publishOptions['currency'] ?: 'MXN';

        $formatMoney = function($amount, $includeCurrency = true) use ($exchangeRate, $currencyCode) {
            $cleanAmount = is_string($amount) ? (float) str_replace(['$', ','], '', $amount) : floatval($amount);
            $converted = $cleanAmount / floatval($exchangeRate);
            $formatted = '$' . number_format($converted, 2);
            return $includeCurrency ? $formatted . ' ' . $currencyCode : $formatted;
        };

        $invoiceItems = $invoice['invoiceItems']->map(function ($item) use ($isEnglish, $formatMoney) {
            return [
                'image' => $item['image'],
                $isEnglish ? 'Item' : 'Concepto' => $item['label'],
                $isEnglish ? 'Description' : 'Descripción' => $item['description'],
                $isEnglish ? 'Qty' : 'Unidades' => $item['units'],
                $isEnglish ? 'Unit Price' : 'V. Unitario' => $formatMoney($item['unit_price'] ?? 0, false),
                'category' => $item['category'],
                'Subtotal' => $formatMoney($item['total'] ?? 0, false),
            ];
        })->toArray();

        $incomes = $invoice['incomes']->map(function ($item) use ($isEnglish, $formatMoney) {
            return [
                $isEnglish ? 'Description' : 'Descripción' => $item['description'],
                $isEnglish ? 'Amount' : 'Monto' => $formatMoney($item['amount'] ?? 0, false),
                $isEnglish ? 'Date' : 'Fecha' => $item['date']
            ];
        })->toArray();

        if ($publishOptions['language'] !== 'es') {
            $openRouterService = new OpenRouterService();
            $targetLanguage = $publishOptions['language'] === 'en' ? 'English' : $publishOptions['language'];

            if (!empty($invoiceItems)) {
                $translatedItems = $openRouterService->translateData($invoiceItems, $targetLanguage);
                // Validate that it returns an array of arrays (not flat strings)
                if (is_array($translatedItems) && !empty($translatedItems) && is_array(reset($translatedItems))) {
                    $invoiceItems = $translatedItems;
                }
            }

            if (!empty($incomes)) {
                $translatedIncomes = $openRouterService->translateData($incomes, $targetLanguage);
                // Validate that it returns an array of arrays (not flat strings)
                if (is_array($translatedIncomes) && !empty($translatedIncomes) && is_array(reset($translatedIncomes))) {
                    $incomes = $translatedIncomes;
                }
            }
        }

        $invoiceItems = collect($invoiceItems);
        $incomes = collect($incomes);

        $invoice['subtotal'] = $formatMoney($invoice['subtotal'] ?? 0);
        $invoice['fee_amount'] = $formatMoney($invoice['fee_amount'] ?? 0);
        $invoice['total'] = $formatMoney($invoice['total'] ?? 0);
        $invoice['iva_amount'] = $formatMoney($invoice['iva_amount'] ?? 0);
        $invoice['subtotal_fee'] = $formatMoney($invoice['subtotal_fee'] ?? 0);
        $invoice['amount_paid'] = $formatMoney($invoice['amount_paid'] ?? 0);
        $invoice['balance'] = $formatMoney($invoice['balance'] ?? 0);

        $data = [
            'invoice' => $invoice,
            'title' => $publishOptions['title'],
            'invoiceItems' => $invoiceItems,
            'incomes' => $incomes,
            'publishOptions' => $publishOptions,
        ];

        $font_data = array(
            'Figtree' => [
                'R' => 'Figtree-VariableFont_wght.ttf',      // regular font
            ]
        );


        $fileName = 'cotización_' . $invoice['id'] . '_' . $publishOptions['title'] . '.pdf';
        $pdf = PDF::Make();
        $pdf->addCustomFont($font_data);
        $pdf->showImageErrors = true;
        $pdf->loadView('pdf.invoice', $data);
        return $pdf->stream($fileName);

    }

    public function publishOrder(Request $request, $id)
    {
        $orderService = new \App\Services\OrderService();
        $order = $orderService->getById($id);

        if (!$order) {
            return abort(404, 'El recurso no fue encontrado.');
        }

        $orderModel = \App\Models\Order::with(['provider', 'categories', 'invoice', 'files'])->find($id);
        $order['provider_name'] = $orderModel->provider ? $orderModel->provider->name : null;
        $order['categories'] = $orderModel->categories->toArray();
        $order['total'] = $orderModel->total;
        $order['image'] = $orderModel->files->first()?->url;

        $data = [
            'order' => $order,
        ];

        $font_data = array(
            'Figtree' => [
                'R' => 'Figtree-VariableFont_wght.ttf',
            ]
        );

        $fileName = 'orden_' . $order['id'] . '.pdf';
        $pdf = PDF::Make();
        $pdf->addCustomFont($font_data);
        $pdf->showImageErrors = true;
        $pdf->loadView('pdf.order', $data);
        return $pdf->stream($fileName);
    }
    public function publishFabrics(Request $request, $invoiceId)
    {
        $invoice = \App\Models\Invoice::with(['project', 'fabrics.provider'])->find($invoiceId);

        if (!$invoice) {
            return abort(404, 'El recurso no fue encontrado.');
        }

        $fabrics = [];
        foreach ($invoice->fabrics as $fabricModel) {
            $fabric = $fabricModel->toArray();
            $fabric['provider_name'] = $fabricModel->provider ? $fabricModel->provider->name : null;
            $fabrics[] = $fabric;
        }

        $data = [
            'invoice' => $invoice,
            'fabrics' => $fabrics,
        ];

        $font_data = array(
            'Figtree' => [
                'R' => 'Figtree-VariableFont_wght.ttf',
            ]
        );

        $fileName = 'telas_cotizacion_' . $invoice->id . '.pdf';
        $pdf = PDF::Make();
        $pdf->addCustomFont($font_data);
        $pdf->showImageErrors = true;
        $pdf->loadView('pdf.fabric', $data);
        return $pdf->stream($fileName);
    }
}
