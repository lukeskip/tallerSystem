<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;

class InvoiceItem extends Model
{
    protected $fillable = [
        'label',
        'description',
        'amount',
        'comission',
        'provider_id',
        'invoice_id',
        'units',
        'unit_price',
        'unit_type'
    ];
    use HasFactory;

    public function getTotalComissionAttribute()
    {   
        $comission = $this->comission / 100;
        $comisionAmount = $comission * ($this->unit_price * $this->units);
        $total = ($this->unit_price * $this->units) + $comisionAmount;
        return number_format($total,2);
    }
    public function getTotalAttribute()
    {   
        
        $total = ($this->unit_price * $this->units) ;
        return number_format($total,2);
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
