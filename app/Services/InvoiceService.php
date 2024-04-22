<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Provider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;


class InvoiceService
{

    public function __construct()
    {
        $this->mainRoute = 'cotizaciones.index';
    }

    public function create($request)
    {
        $request['id'] = Utils::generateInvoiceId();
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $invoice =  Invoice::create($validatedData['data']);   
            return Inertia::location(route('cotizaciones.show',$invoice->id));
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }
        return Inertia::location(route($this->mainRoute));
    }

    public function update(Invoice $invoice, array $data)
    {
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $invoice = Invoice::find($id);
            $invoice->update($validatedData['data']);
            return response()->json(['redirect'=> 'invoice/'.$invoice->id]);   
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }      
    }

    public function getItemCategories($id){
        $invoice = Invoice::find($id);
        $categories = [];
        foreach ($invoice->invoiceItems as $item) {
            if ($item->category && !in_array($item->category, $categories)) {
                $categories[] = $item->category;
            }
        }
        return $categories;
    }

    public function delete(Invoice $invoice)
    {
        $invoice->delete();
        return Inertia::location(route($this->mainRoute));
    }

    public function getById($id)
    {
        $invoice = Invoice::with(['incomes','outcomes','invoiceItems' => function ($query) {
            $query
            ->orderBy('category', 'asc')
            ->orderBy('created_at', 'desc');
        }])->find($id);

        $invoiceItems = $invoice->invoiceItems->map(function ($item) {
            return [
                "id"=>$item->id,
                "label"=>$item->label,
                "description"=>$item->description,
                "units"=>$item->units,
                "category"=>$item->category,
                "unit_price"=>"$".$item->unit_price,
                "total"=>"$".$item->total,
                "comission"=>$item->comission."%",
                "total_comission"=>"$".$item->total_comission,
            ];
        });

        $incomes = $invoice->incomes->map(function ($item) {
            return [
                "id"=>$item->id,
                "description"=>$item->description,
                "type"=>$item->type,
                "amount"=>$item->amount,
                "reference"=>$item->reference,
                "image"=>$item->image,
                "invoice_id"=>$item->invoice_id,
                "date"=>$item->format_date,    
            ];
        });

        $outcomes = $invoice->outcomes->map(function ($item) {
            return [
                "id"=>$item->id,
                "description"=>$item->description,
                "type"=>$item->type,
                "amount"=>$item->amount,
                "reference"=>$item->reference,
                "image"=>$item->image,
                "status"=>$item->status,
                "date"=>$item->format_date,
               
            ];
        });
       
        
        if($invoice){
            return [
                'id'=>$invoice->id,
                'project'=>$invoice->project->name,
                'client'=>$invoice->project->client->name,
                'amount'=>$invoice->amount,
                'invoiceItems' => $invoiceItems,
                'incomes' => $incomes,
                'outcomes' => $outcomes,
                'balance' => $invoice->balance,
                'format_date'=>$invoice->format_date,
            ];
        }else {
            return null;
        }
    }

    public function getAll()
    {

        $invoices = Invoice::with('project')->paginate();
        
        $invoices->getCollection()->transform(function ($invoice) {
            return [
                'id' => $invoice->id,
                'file' => $invoice->id,
                'project' => $invoice->project->name,
                'client'=>$invoice->project->client->name,
                'amount'=>$invoice->amount,
                'format_date' => $invoice->format_date,
            ];
        });

        return $invoices;
    }

    public function getProviders()
    {

        $providers = Provider::all();
        
        $providers->transform(function ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'contact_name' => $provider->contact_name,
            ];
        });

        return $providers;
    }

    public function getInvoices (){
        $invoices = Invoice::all();
        
        $invoices->transform(function ($invoice) {
            return [
                'id' => $invoice->id,
                'name' => $invoice->project->name." ".$invoice->project->client->name,    
            ];
        });

        return $invoices;
    }

    protected function validateData($request){
        $validator = Validator::make($request->all(), [
            'id'=>'required|string',
            'status' => 'required|string',
            'project_id' => 'required|numeric|gt:0',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            $fieldErrors = [];
            foreach ($errors->messages() as $field => $messages) {
                $fieldErrors[$field] = $messages;
            }

            return ['status'=>false,'errors'=>$fieldErrors];
        }

        $cleanedData = $validator->validated();

        return ['status'=>true,'data'=>$cleanedData];
    }
}
