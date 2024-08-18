<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;
use App\Models\Outcome;

class Income extends Model
{
    protected $fillable = [
        'amount',
        'description',
        'invoice_id',
        'reference',
        'type',
        'image',
    ];

    use HasFactory;

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
