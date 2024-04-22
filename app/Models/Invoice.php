<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use App\Utils\Utils;

class Invoice extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'project_id',
        'status',
    ];

    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class,"client_id");
    }

    public function project()
    {
        return $this->belongsTo(Project::class,"project_id");
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }

    public function getAmountAttribute()
    {   
        $totalComissions = 0;
        
        foreach ($this->invoiceItems as $item) {
            $totalComissions += floatval(str_replace(',', '', $item->total_comission));
        }
        return "$" . number_format($totalComissions, 2);
        
    }
    public function getBalanceAttribute()
    {   
        $totalComissions = 0;
        $totalIncomes = 0;
        
        foreach ($this->invoiceItems as $item) {
            $totalComissions += floatval(str_replace(',', '', $item->total_comission));
        }

        foreach ($this->incomes as $item) {
            $totalIncomes += floatval(str_replace(',', '', $item->amount));
        }

        $balance = $totalComissions - $totalIncomes;
        return "$" . number_format($balance, 2);
        
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
    public function outcomes()
    {
        return $this->hasMany(Outcome::class);
    }
}
