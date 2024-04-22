<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Inertia\Inertia;
use App\Utils\Utils;

class InvoiceController extends Controller
{
    public function __construct(InvoiceService $invoiceService)
    {
        $this->service = $invoiceService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoices = $this->service->getAll($request);
        return Inertia::render('Invoice/Invoices', [
            'invoices' => $invoices,
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $fields = Utils::getFields('invoices');
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return  $this->service->create($request);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {   
        $providers = $this->service->getProviders();
        $invoice = $this->service->getById($id);
        return Inertia::render('Invoice/InvoiceDetail', [
            'invoice' => $invoice,
            'providers'=> $providers
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = $this->service->getById($id,true);
        $fields = Utils::getFields('projects');
        return response()->json(["item"=>$item,"fields"=>$fields]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $invoice = $this->service->delete($id);
    }
}
