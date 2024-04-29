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

   

    public function create(){
        return $fields = Utils::getFields('projects');
    }

    public function store($request)
    {
        return $invoice =  Invoice::create($request);    
    }

    public function update(Invoice $invoice, $request)
    {
            $invoice = Invoice::find($id);
            $invoice->update($request);
            return response()->json(['redirect'=> 'invoice/'.$invoice->id]);     
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

    public function delete($id)
    {
        $invoice = Invoice::find($id);
        $projectID = $invoice->project_id;
        $invoice->delete($id);

        return $projectID;


    }

    public function getById($id)
    {
        $invoice = Invoice::with(['incomes','project','outcomes','invoiceItems' => function ($query) {
            $query
            ->orderBy('category', 'asc')
            ->orderBy('created_at', 'desc');
        }])->find($id);

        
       
        
        if($invoice){

            $invoiceItems = $invoice->invoiceItems->map(function ($item) {
                return [
                    "id"=>$item->id,
                    "label"=>$item->label,
                    "description"=>$item->description,
                    "units"=>$item->units,
                    "category"=>$item->category,
                    "unit_price"=>Utils::publishMoney($item->unit_price),
                    "total"=>"$".$item->total,
                    "comission"=>Utils::publishPercentage($item->comission),
                    "provider"=> $item->provider->name ?? '',
                    "total_comission"=>Utils::publishMoney($item->total_comission),
                ];
            });
    
            $incomes = $invoice->incomes->map(function ($item) {
                return [
                    "id"=>$item->id,
                    "description"=>$item->description,
                    "type"=>$item->type,
                    "amount"=>Utils::publishMoney($item->amount),
                    "reference"=>$item->reference,
                    "image"=>$item->image,
                    "invoice_id"=>$item->invoice_id,
                    "date"=>$item->format_date,    
                ];
            });
    
            $outcomes = $invoice->outcomes->map(function ($item) {
                return [
                    "id"=>$item->id,
                    "provider"=>$item->provider->name,
                    "description"=>$item->description,
                    "type"=>$item->type,
                    "amount"=>Utils::publishMoney($item->amount),
                    "reference"=>$item->reference,
                    "image"=>$item->image,
                    "status"=>$item->status,
                    "date"=>$item->format_date,
                   
                ];
            });

            return [
                'id'=>$invoice->id,
                'project'=>$invoice->project->name,
                'comission'=>$invoice->project->comission,
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

    public function getAll($request)
    {

        $invoices = Invoice::with('project')->orderBy('id','desc');

        if ($request &&  $request->input('search')) {
            $invoices->where('id', 'like', '%' . $request->input('search') . '%')
            ->orWhereHas('project', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            });
        }

        $invoices = $invoices->paginate();
        
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

}
