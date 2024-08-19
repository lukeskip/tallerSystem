<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;
use App\Utils\Utils;

class Note extends Model
{
    protected $fillable = [
        'content',
        'status',
        
    ];
    use HasFactory;

    public function invoiceItems()
    {
        return $this->belongsToMany(InvoiceItem::class);
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
