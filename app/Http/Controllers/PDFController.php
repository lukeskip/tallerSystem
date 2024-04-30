<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\InvoiceService;


class PDFController extends Controller
{
    
    public function publish($id){
        $InvoiceService = new InvoiceService();

        $invoice = $InvoiceService->getById($id); 

        $invoiceItems = $invoice['invoiceItems']->map(function ($item){
            return [
                'Concepto'=> $item['label'],
                'Descripción'=> $item['description'],
                'Unidades'=> $item['units'],
                'Valor Unitario'=> $item['unit_price'],
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



        $html = view('pdf.invoice', $data)->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->set_option('isRemoteEnabled', true);
        
        $fileName = 'cotización_' . $invoice['id'] . '.pdf';

        return $pdf->download($fileName);

    }

    public function test($id){
        $InvoiceService = new InvoiceService();

        $invoice = $InvoiceService->getById($id); 

        $invoiceItems = $invoice['invoiceItems']->map(function ($item){
            return [
                'Concepto'=> $item['label'],
                'Descripción'=> $item['description'],
                'Unidades'=> $item['units'],
                'Valor Unitario'=> $item['unit_price'],
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
