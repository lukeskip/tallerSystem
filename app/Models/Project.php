<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class,"client_id");
    }

    public function getFormatDateAttribute()
    {
        return Carbon::parse($this->created_at)->translatedFormat('D d/m');
    }
}
