<?php
namespace App\Services;

use App\Models\InvoiceItem;

class InvoiceItemService
{
    public function create(Array $data)
    {
        return InvoiceItem::create($data);
    }

    public function update($id, array $data)
    {
        $invoiceItem = InvoiceItem::find($id);
        $invoiceItem->update($data);
        return $invoiceItem;    
    }

    public function delete($id)
    {
        $invoiceItem = InvoiceItem::find($id);
        $invoiceItem->delete();
    }

    public function getById($id)
    {
        return InvoiceItem::find($id);
    }

    public function getAll()
    {
        return InvoiceItem::all();
    }
}