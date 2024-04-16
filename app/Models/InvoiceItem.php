<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;

class InvoiceItem extends Model
{
    use HasFactory;

    public function getAmountComissionAttribute()
    {   
        $comission = $this->comission / 100;
        $comisionAmount = $comission * $this->amount;
        $total = $this->amount + $comisionAmount;
        return number_format($total,2);
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
