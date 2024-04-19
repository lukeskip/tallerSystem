<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Services\InvoiceItemService;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;

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
        
        $fields = Utils::getFields('invoice_items');
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $invoiceItem = $this->invoiceItemService->create($request);
        
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
    public function edit($id)
    {
        $invoiceItem = $this->invoiceItemService->getById($id,true);
        $fields = Utils::getFields('invoice_items');
        
        return response()->json(["invoice"=>$invoiceItem,"fields"=>$fields]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return $this->invoiceItemService->update($id,$request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->invoiceItemService->delete($id);
    }
}
