<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;
use App\Models\Project;
use App\Models\InvoiceItem;

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

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function invoiceItems()
    {
        return $this->belongsToMany(InvoiceItem::class);
    }
    
    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
