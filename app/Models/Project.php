<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use Carbon\Carbon;
use App\Utils\Utils;

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
        return Utils::formatDate($this->created_at);
    }
}
