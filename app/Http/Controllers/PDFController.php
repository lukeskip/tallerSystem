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
            'include_images' => $request->boolean('include_images', false),
            'include_labels' => $request->boolean('include_labels', false),
        ];

        $isEnglish = $publishOptions['language'] !== 'es';
        $exchangeRate = $publishOptions['exchange_rate'] ?: 1;
        $currencyCode = $publishOptions['currency'] ?: 'MXN';

        $formatMoney = function($amount, $includeCurrency = true) use ($exchangeRate, $currencyCode) {
            $cleanAmount = is_string($amount) ? (float) str_replace(['$', ','], '', $amount) : floatval($amount);
            $converted = $cleanAmount / floatval($exchangeRate);
            if ($converted < 0) {
                $formatted = '-$' . number_format(abs($converted), 2);
            } else {
                $formatted = '$' . number_format($converted, 2);
            }
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
                'label_color' => $item['label_color'] ?? null,
                'label_comment' => $item['label_comment'] ?? null,
                'show_label_in_pdf' => $item['show_label_in_pdf'] ?? false,
                'discount_str' => !empty($item['discount']) ? ($item['discount'] . ($item['discount_type'] === 'percentage' ? '%' : '') . ' (-' . $formatMoney($item['discount_amount'] ?? 0, false) . ')') : null,
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
        $invoice['subtotal_after_extras_before_fee'] = $formatMoney($invoice['subtotal_after_extras_before_fee'] ?? 0);
        $invoice['fee_amount'] = $formatMoney($invoice['fee_amount'] ?? 0);
        $invoice['total'] = $formatMoney($invoice['total'] ?? 0);
        $invoice['iva_amount'] = $formatMoney($invoice['iva_amount'] ?? 0);
        $invoice['subtotal_fee'] = $formatMoney($invoice['subtotal_fee'] ?? 0);
        $invoice['amount_paid'] = $formatMoney($invoice['amount_paid'] ?? 0);
        $invoice['balance'] = $formatMoney($invoice['balance'] ?? 0);

        $mapExtra = function($extra) use ($formatMoney) {
            return [
                'id' => $extra['id'],
                'label' => $extra['label'],
                'value' => $extra['value'],
                'calculation_basis' => $extra['calculation_basis'],
                'is_discount' => $extra['is_discount'] ?? false,
                'label_color' => $extra['label_color'] ?? null,
                'amount' => $formatMoney($extra['amount_raw'] ?? 0),
            ];
        };

        if (isset($invoice['extras'])) {
            $invoice['extras'] = collect($invoice['extras'])->map($mapExtra)->toArray();
        }
        if (isset($invoice['extras_before_fee'])) {
            $invoice['extras_before_fee'] = collect($invoice['extras_before_fee'])->map($mapExtra)->toArray();
        }
        if (isset($invoice['extras_after_fee'])) {
            $invoice['extras_after_fee'] = collect($invoice['extras_after_fee'])->map($mapExtra)->toArray();
        }

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
