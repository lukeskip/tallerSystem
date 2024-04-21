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
        
        $data = [
            'invoice' => $invoice,
            'invoiceItems' => $invoiceItems,
        ];



        $html = view('pdf.invoice', $data)->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->set_option('isRemoteEnabled', true);
        
        $fileName = 'cotización_' . $invoice['id'] . '.pdf';

        return $pdf->download($fileName);

    }
}
