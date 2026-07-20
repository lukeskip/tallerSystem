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
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
