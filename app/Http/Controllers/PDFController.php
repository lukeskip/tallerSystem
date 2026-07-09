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
        ];

        $isEnglish = $publishOptions['language'] !== 'es';

        $invoiceItems = $invoice['invoiceItems']->map(function ($item) use ($isEnglish) {
            return [
                $isEnglish ? 'Item' : 'Concepto' => $item['label'],
                $isEnglish ? 'Description' : 'Descripción' => $item['description'],
                $isEnglish ? 'Qty' : 'Unidades' => $item['units'],
                $isEnglish ? 'Unit Price' : 'V. Unitario' => $item['unit_price'],
                'category' => $item['category'],
                'Subtotal' => $item['total'],
            ];
        })->toArray();

        $incomes = $invoice['incomes']->map(function ($item) use ($isEnglish) {
            return [
                $isEnglish ? 'Description' : 'Descripción' => $item['description'],
                $isEnglish ? 'Amount' : 'Monto' => $item['amount'],
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


}
