<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Project;

class Client extends Model
{
    use HasFactory;

    public function projects(){
        return $this->hasMany(Project::class);
    }

    public function getFormatDateAttribute()
    {
        return Carbon::parse($this->created_at)->translatedFormat('D d/m Y');
    }

}
