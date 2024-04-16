<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Project;
use App\Utils\Utils;

class Client extends Model
{
    use HasFactory;

    public function projects(){
        return $this->hasMany(Project::class);
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }

}
