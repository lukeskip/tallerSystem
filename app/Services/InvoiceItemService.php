<?php
namespace App\Services;

use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class InvoiceItemService
{
    public function create($request)
    {

        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $item =  InvoiceItem::create($validatedData['data']);
            return response()->json(['redirect'=> 'cotizaciones/'.$item->invoice_id]);
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }
        
    }

    public function update($id, $request)
    {
        
        $validatedData = $this->validateData($request);
        
        if($validatedData['status']){
            $invoiceItem = InvoiceItem::find($id);
            $invoiceItem->update($validatedData['data']);
            return response()->json(['redirect'=> 'cotizaciones/'.$invoiceItem->invoice_id]);  
              
        }else{
            return response()->json(['errors'=>$validatedData['errors']], 422);
        }

    }

    public function delete($id)
    {
        $invoiceItem = InvoiceItem::find($id);
        $invoiceItem->delete();
    }

    public function getById($id,$edit = false)
    {
        $invoice = InvoiceItem::find($id);
        if($edit && $invoice){
            return [
                'label'=> ['value'=>$invoice->label,'type'=>'string'],
                'description'=> ['value'=>$invoice->description,'type'=>'string'],
                'unit_price'=> ['value'=>$invoice->unit_price,'type'=>'number'],
                'unit_type'=> ['value'=>$invoice->unit_type,'type'=>'string'],
                'units'=> ['value'=>$invoice->units,'type'=>'number'],
                'comission'=> ['value'=>$invoice->comission,'type'=>'number'],
                'provider_id'=> ['value'=>$invoice->provider_id,'type'=>'number'],
            ];
        }

        return $invoice;
    }
    
    public function getAll()
    {
        return InvoiceItem::all();
    }

    protected function validateData($request){
        $validator = Validator::make($request->all(), [
            'label' => 'required|string',
            'description' => 'required|string',
            'comission' => 'required|numeric|gt:0',
            'units' => 'required|numeric',
            'unit_price' => 'required|numeric|gt:0',
            'unit_type' => 'required|string',
            'provider_id'=> 'numeric'
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

        if($cleanedData['provider_id'] === "0" || $cleanedData['provider_id'] === 0){
            $cleanedData['provider_id']= null;
        }

        return ['status'=>true,'data'=>$cleanedData];
    }
}