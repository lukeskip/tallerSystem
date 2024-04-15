<?php
namespace App\Services;

use App\Models\InvoiceItem;

class InvoiceItemService
{
    public function create(array $data)
    {
        return InvoiceItem::create($data);
    }

    public function update(InvoiceItem $invoiceItem, array $data)
    {
        $invoiceItem->update($data);
        return $invoiceItem;    
    }

    public function delete(InvoiceItem $invoiceItem)
    {
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