<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',       
        'invoice_id', 
        'order'
    ];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
