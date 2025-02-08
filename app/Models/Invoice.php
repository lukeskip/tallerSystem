<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\InvoiceItem;
use App\Models\Category;
use Carbon\Carbon;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'project_id',
        'status',
        'agent_comission',
        'iva',
        'fee',
    ];

    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class, "client_id");
    }

    public function project()
    {
        return $this->belongsTo(Project::class, "project_id");
    }

    public function categories()
    {
        return $this->hasMany(Category::class)->orderBy('order');
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }

    public function getSubtotalAttribute()
    {
        if ($this->invoiceItems) {
            return $this->invoiceItems->sum('total');
        } else {
            return 0;
        }
    }

    public function getTotalAttribute()
    {
        $subtotal = $this->getSubtotalAttribute();
        $ivaAmount = $this->getIvaAmountAttribute();
        $feeAmount = $this->getFeeAmountAttribute();

        return $subtotal + $ivaAmount + $feeAmount;
    }

    public function getBalanceAttribute()
    {

        $total = $this->getTotalAttribute();
        $totalIncomes = $this->getAmountPaidAttribute();

        return $balance = $total - $totalIncomes;
    }
    public function getFeeAmountAttribute()
    {

        if ($this->fee > 0) {
            $fee = $this->fee / 100;
            $amountFee = $this->getSubtotalAttribute() * $fee;

            return $amountFee;
        } else {
            return 0;
        }
    }
    public function getSubtotalFeeAttribute()
    {
        $subtotal = $this->getSubtotalAttribute();
        $fee = $this->getFeeAmountAttribute();
        return $subtotal + $fee;
    }

    public function getIvaAmountAttribute()
    {

        if ($this->iva > 0) {
            $iva = $this->iva / 100;
            $amountIVA = $this->getSubtotalFeeAttribute() * $iva;
            return $amountIVA;
        } else {
            return 0;
        }
    }

    public function getAmountPaidAttribute()
    {
        if ($this->incomes) {
            return $this->incomes->sum('amount');
        } else {
            return 0;
        }
    }
    public function getDebtAttribute()
    {
        return 0;
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
