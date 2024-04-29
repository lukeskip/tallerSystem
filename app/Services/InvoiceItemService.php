<?php
namespace App\Services;

use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;

class InvoiceItemService
{
    public function store($request)
    {
        return InvoiceItem::create($request);     
    }

    public function create(){
        return $fields = Utils::getFields('invoice_items');
    }

    public function edit($id){
        $invoiceItem =  InvoiceItem::find($id);
        $invoice_id = $invoiceItem->invoice_id;
        $invoiceItem =  [
            'label'=> ['value'=>$invoiceItem->label,'type'=>'string'],
            'description'=> ['value'=>$invoiceItem->description,'type'=>'string'],
            'unit_price'=> ['value'=>$invoiceItem->unit_price,'type'=>'number'],
            'unit_type'=> ['value'=>$invoiceItem->unit_type,'type'=>'string'],
            'units'=> ['value'=>$invoiceItem->units,'type'=>'number'],
            'comission'=> ['value'=>$invoiceItem->comission,'type'=>'number'],
            'provider_id'=> ['value'=>$invoiceItem->provider_id,'type'=>'number'],
            'category'=> ['value'=>$invoiceItem->category,'type'=>'string'],
        ];
    
        $fields = Utils::getFields('invoice_items',$invoice_id);
        return ["item"=>$invoiceItem,"fields"=>$fields];
        
    }

    public function update($id, $request)
    {
        $invoiceItem = InvoiceItem::find($id);
        $invoiceItem->update($request);
        return $invoiceItem;      
    }

    public function delete($id)
    {
        $invoiceItem = InvoiceItem::find($id);
        $invoiceItem->delete();
    }

    public function getById($id,$edit = false)
    {
       return  $invoice = InvoiceItem::find($id);
       
    }
    
    public function getAll()
    {
        return InvoiceItem::all();
    }

}