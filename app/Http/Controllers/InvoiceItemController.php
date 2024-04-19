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
        $validator = Validator::make($request->all(), [
            'label' => 'required|string',
            'description' => 'required|string',
            'comission' => 'required|numeric|gt:0',
            'units' => 'required|numeric',
            'unit_price' => 'required|numeric|gt:0',
            'unit_type' => 'required|string',
            'invoice_id'=> 'required|numeric'
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            $fieldErrors = [];
            foreach ($errors->messages() as $field => $messages) {
                $fieldErrors[$field] = $messages;
            }
            return response()->json(['success' => false, 'errors' => $fieldErrors], 422);
        }

        $invoiceItem = $this->invoiceItemService->create($validator->validated());
        
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
    public function destroy($id)
    {
        $this->invoiceItemService->delete($id);
    }
}
