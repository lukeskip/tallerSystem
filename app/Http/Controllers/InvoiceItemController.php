<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Services\InvoiceItemService;

class InvoiceItemController extends Controller
{
    protected $invoiceItemService;

    public function __construct(InvoiceItemService $invoiceItemService)
    {
        $this->invoiceItemService = $invoiceItemService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoiceItems = $this->invoiceItemService->getAll();
        
        return Inertia::render('InvoiceItems/Index', [
            'invoiceItems' => $invoiceItems,
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $request->validate([
            'description' => 'required|string',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $invoiceItem = $this->invoiceItemService->create($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceItem $invoiceItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceItem $invoiceItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceItem $invoiceItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceItem $invoiceItem)
    {
        //
    }
}
