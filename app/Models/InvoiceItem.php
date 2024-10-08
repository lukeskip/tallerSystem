<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;
use App\Models\Provider;
use App\Models\Invoice;
use App\Models\Note;

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
        'unit_type',
        'category'
    ];
    use HasFactory;

    public function provider (){
        return $this->belongsTo(Provider::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class,'invoice_item_file');
    }

    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,"invoice_id");
    }

    public function getTotalComissionAttribute()
    {   
        $comission = $this->comission / 100;
        $comisionAmount = $comission * ($this->unit_price * $this->units);
        $total = ($this->unit_price * $this->units) + $comisionAmount;
        return $total;
    }
    public function getUnitComissionAttribute()
    {   
        $comission = $this->comission / 100;
        $comisionAmount = $comission * ($this->unit_price * $this->units);
        $total = $this->unit_price  + $comisionAmount;
        return $total;
    }
    public function getTotalAttribute()
    {   
        
        $total = ($this->unit_price * $this->units) ;
        return number_format($total,2);
    }
    public function getAmountAttribute()
    {   
        
        $total = ($this->unit_price * $this->units) ;
        return $total;
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }

    
}
