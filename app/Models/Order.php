<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Invoice;
use App\Models\Provider;
use App\Models\Category;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'provider_id',
        'description',
        'unit_cost',
        'units',
        'has_iva',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getTotalAttribute()
    {
        return $this->unit_cost * $this->units;
    }
}
