<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\InvoiceService;
use PDF;




class PDFController extends Controller
{
    
    public function publish($id){
        $InvoiceService = new InvoiceService();

        $invoice = $InvoiceService->getById($id); 

        if(!$invoice){
            return abort(404, 'El recurso no fue encontrado.'); 
        }

        $invoiceItems = $invoice['invoiceItems']->map(function ($item){
            return [
                'Concepto'=> $item['label'],
                'Descripción'=> $item['description'],
                'Unidades'=> $item['units'],
                'V. Unitario'=> $item['unit_price'],
                'category'=> $item['category'],
                'Subtotal'=> $item['total_comission'],
            ];
        });

        $incomes = $invoice['incomes']->map(function ($item){
            return [
                'Descripción'=> $item['description'],
                'Monto'=> $item['amount'],
                'Fecha'=>$item['format_date']
            ];
        });
        
        $data = [
            'invoice' => $invoice,
            'invoiceItems' => $invoiceItems,
            'incomes'=> $incomes,
        ];

        $font_data = array(
            'Figtree' => [
                'R' => 'Figtree-VariableFont_wght.ttf',      // regular font
            ]
        );
    
        
        $fileName = 'cotización_' . $invoice['id'] . '.pdf';
        $pdf = PDF::Make();
        $pdf->addCustomFont($font_data);
        $pdf->showImageErrors = true;
        $pdf->loadView('pdf.invoice', $data);
        return $pdf->stream($fileName);

        // return $pdf->download($fileName);

    }

    public function test($id){
        $InvoiceService = new InvoiceService();

        $invoice = $InvoiceService->getById($id); 

        $invoiceItems = $invoice['invoiceItems']->map(function ($item){
            return [
                'Concepto'=> $item['label'],
                'Descripción'=> $item['description'],
                'Unidades'=> $item['units'],
                'V. Unitario'=> $item['unit_price'],
                'category'=> $item['category'],
                'Subtotal'=> $item['total_comission'],
            ];
        });


        dump($invoice['incomes']);
        $incomes = $invoice['incomes']->map(function ($item){
            return [
                'Descripción'=> $item['description'],
                'Monto'=> $item['amount'],
                'Fecha'=>$item['format_date']
            ];
        });
        

        
        $data = [
            'invoice' => $invoice,
            'invoiceItems' => $invoiceItems,
            'incomes' => $incomes,
        ];

        return view('pdf.invoice', $data)->render();
        

    }
}
