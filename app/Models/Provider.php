<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'address',
        'phone',
    ];
    
    use HasFactory;
    

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
