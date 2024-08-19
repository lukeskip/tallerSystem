<?php
namespace App\Services;

use App\Models\InvoiceItem;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;
use League\Csv\Reader;
use App\Services\ValidateDataService;
use Illuminate\Validation\Rule;

class InvoiceItemService
{   

    public function rules ($invoice_id = null, $category = null){
        return $rules = [
            'label' => [
                'required',
                'string',
                Rule::unique('invoice_items')
                ->where(function ($query) use ($invoice_id,$category) {
                    return $query->where('invoice_id', $invoice_id)
                                 ->where('category', $category);
                })
            ],
            'description' => 'nullable|string',
            'comission' => 'nullable|numeric|min:0',
            'units' => 'required|numeric',
            'unit_price' => 'required|numeric|gt:0',
            'provider_id'=> 'nullable',
            'invoice_id'=> 'string|nullable',
            'category'  => 'nullable'
        ];
    }

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
       return  $invoiceItem = InvoiceItem::with(['files','notes','invoice'])->find($id);
       
    }
    
    public function getAll()
    {
        return InvoiceItem::all();
    }

    public function importCSV ($request,$invoiceId){
        
         $file = $request->file('file');
    
         $csv = Reader::createFromPath($file->getPathname(), 'r');
         
         $records = $csv->getRecords();

         $isFirstRecord = true;
         $count = 0;
         $countSuccess = 0;
         $errors = [];
        
         foreach ($records as $record) {
            if ($count === 0) {
                $count++;
                continue; 
            }

            $request = [
                'label'=> $record[0],
                'category'=> $record[1],
                'unit_price'=> $record[2],
                'units'=> $record[3],
                'comission'=> $record[4],
                'description'=> $record[5],
                'invoice_id'=> $invoiceId,
            ];

            

            $validatedData = new ValidateDataService($request, $this->rules($invoiceId,$request['category']));
            $validatedData = $validatedData->getValidatedData();
        
            if($validatedData['status']){
                
                $invoiceItem = InvoiceItem::create($validatedData['data']);   
                if($invoiceItem){
                    $countSuccess++;
                } else{
                    $invoiceItem;
                }
            }else{
                $errors[] = ["cell"=> $count,'errors'=>$validatedData['errors']];
            }   

            $count++;
            
        }
        
        if($errors){
            return response()->json(
                ['message'=>"Importado con los siguientes errores",'errors'=>$errors], 422);
        }else{
            return response()->json(['message'=>"importado con éxito, se crearon $countSuccess registros"], 200);

        }

 
    }
}