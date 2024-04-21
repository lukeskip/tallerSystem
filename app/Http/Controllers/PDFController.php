<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\InvoiceService;


class PDFController extends Controller
{
    
    public function publish($id){
        $InvoiceService = new InvoiceService();

        $invoiceItems = $InvoiceService->getById('2024_0006'); 
        
        $data = [
            'invoiceItems' => $invoiceItems['invoiceItems'],
        ];

        $html = view('pdf.invoice', $data)->render();

        // return dump($html);
        $pdf = Pdf::loadHTML($html);
        return $pdf->download();

    }
}
