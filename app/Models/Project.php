<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use App\Utils\Utils;

class Project extends Model
{
    protected $fillable = [
        'name',
        'address',
        'comission',
        'client_id',
        'user_id',
    ];

    use HasFactory;

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class,"client_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
