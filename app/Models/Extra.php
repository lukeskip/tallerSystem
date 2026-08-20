<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Extra extends Model
{
    use HasFactory;

    protected $table = 'invoice_extras';

    protected $fillable = [
        'invoice_id',
        'label',
        'value',
        'type',
        'calculation_basis',
        'is_discount',
        'label_color',
    ];

    protected $casts = [
        'is_discount' => 'boolean',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
