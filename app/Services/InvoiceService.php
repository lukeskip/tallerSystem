<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Provider;

class InvoiceService
{
    public function create(array $data)
    {
        return Invoice::create($data);
    }

    public function update(Invoice $invoice, array $data)
    {
        $invoice->update($data);
        return $invoice;    
    }

    public function delete(Invoice $invoice)
    {
        $invoice->delete();
    }

    public function getById($id)
    {
        $invoice = Invoice::with("invoiceItems")->find($id);
        if($invoice){
            return [
                'id'=>$invoice->id,
                'client'=>$invoice->project->client->name,
                'amount'=>$invoice->amount,
                'invoiceItems'=> $invoice->invoiceItems->map(function ($item) {
                    return [
                        "label"=>$item->label,
                        "description"=>$item->description,
                        "units"=>$item->units,
                        "unit_price"=>"$".$item->unit_price,
                        "total"=>"$".$item->total,
                        "comission"=>$item->comission."%",
                        "total_comission"=>"$".$item->total_comission,
                    ];
                }),
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
}
