<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Services\InvoiceItemService;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Utils\Utils;
use App\Services\ValidateDataService;

class InvoiceItemController extends Controller
{
    protected $invoiceItemService;

    public function __construct(InvoiceItemService $invoiceItemService)
    {
        $this->middleware('can:read invoice_item', ['only' => ['index', 'show']]);
        $this->middleware('can:create invoice_item', ['only' => ['create', 'store']]);
        $this->middleware('can:edit invoice_item', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete invoice_item', ['only' => ['destroy']]);

        $this->service = $invoiceItemService;
        $this->rules = $this->service->rules();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoiceItems = $this->service->getAll();
        
        return Inertia::render('InvoiceItems/Index', [
            'invoiceItems' => $invoiceItems,
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        if(isset($_GET['parentId'])){
            $id = $_GET['parentId'];
        }else{
            $id = false;
        }
        
        $fields = Utils::getFields('invoice_items',$id);
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($validatedData['status']){
            return $item = $this->service->store($validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        } 
        
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
        $fields = $this->service->edit($id);
        return response()->json($fields);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = new ValidateDataService($request->all(), $this->rules);
        $validatedData = $validatedData->getValidatedData();

        if($validatedData['status']){
            $item = $this->service->update($id,$validatedData['data']);    
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function importCSV(Request $request, $id)
    {   
        return $this->service->importCSV($request,$id);
    }
}
