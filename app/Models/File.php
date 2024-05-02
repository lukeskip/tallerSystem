<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;

class File extends Model
{

    protected $fillable = [
        'project_id',
        'url',
        'name',
        'extension',
        'preview',
        'public_id',
    ];
    use HasFactory;

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
