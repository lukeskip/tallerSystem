<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\InvoiceItem;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class,"client_id");
    }

    public function project()
    {
        return $this->belongsTo(Project::class,"project_id");
    }

    public function getFormatDateAttribute()
    {
        return Carbon::parse($this->created_at)->translatedFormat('D d/m');
    }

    public function getAmountAttribute()
    {
        return "$".number_format($this->invoiceItems->sum('amount'),2);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
