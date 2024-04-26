<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;
use App\Models\InvoiceItem;
use App\Models\Outcome;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'address',
        'phone',
    ];
    
    use HasFactory;
    
    public function invoiceItems (){
        return $this->hasMany(InvoiceItem::class);
    }
    public function outcomes (){
        return $this->hasMany(Outcome::class);
    }


    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
