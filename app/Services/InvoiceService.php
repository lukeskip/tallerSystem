<?php

namespace App\Services;

use App\Models\Invoice;

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
        return Invoice::find($id);
    }

    public function getAll()
    {

        $invoices = Invoice::with('project')->get()->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'format_date' => $invoice->format_date,
                'project' => [
                    'id' => $invoice->project->id,
                    'name' => $invoice->project->name,
                ],
                'cliente'=>$invoice->project->client->name
            ];
        });

        return $invoices;
    }
}
