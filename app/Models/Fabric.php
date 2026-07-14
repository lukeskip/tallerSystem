<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'provider_id',
        'brand',
        'pattern',
        'color',
        'units',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
