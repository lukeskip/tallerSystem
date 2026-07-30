<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'comment',
        'show_in_pdf',
    ];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'item_label_id');
    }
}
