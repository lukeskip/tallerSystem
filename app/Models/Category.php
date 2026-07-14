<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\Order;

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

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function getTotalAttribute()
    {
        return $this->invoiceItems->sum('total');
    }
}
