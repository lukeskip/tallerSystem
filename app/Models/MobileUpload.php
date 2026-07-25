<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'file_id',
        'invoice_item_id',
        'status',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }
}
