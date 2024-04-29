<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;
use App\Models\Provider;

class Outcome extends Model
{
    protected $fillable = [
        'amount',
        'description',
        'invoice_id',
        'reference',
        'status',
        'type',
        'image',
        'provider_id',
        'income_id',
    ];

    use HasFactory;

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class,"provider_id");
    }
}
