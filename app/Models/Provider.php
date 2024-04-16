<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;

class Provider extends Model
{
    use HasFactory;

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
