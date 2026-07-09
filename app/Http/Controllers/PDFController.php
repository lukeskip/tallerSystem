<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\InvoiceService;
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

        $invoiceItems = $invoice['invoiceItems']->map(function ($item) {
            return [
                'Concepto' => $item['label'],
                'Descripción' => $item['description'],
                'Unidades' => $item['units'],
                'V. Unitario' => $item['unit_price'],
                'category' => $item['category'],
                'Subtotal' => $item['total'],
            ];
        });

        $incomes = $invoice['incomes']->map(function ($item) {
            return [
                'Descripción' => $item['description'],
                'Monto' => $item['amount'],
                'Fecha' => $item['date']
            ];
        });

        $publishOptions = [
            'title' => $request->input('title'),
            'currency' => $request->input('currency', 'MXN'),
            'exchange_rate' => $request->input('exchange_rate', 1),
            'language' => $request->input('language', 'es'),
        ];

        $data = [
            'invoice' => $invoice,
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
