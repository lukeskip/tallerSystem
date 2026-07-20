<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\InvoiceItem;
use App\Models\Category;
use App\Models\Order;
use Carbon\Carbon;
use App\Utils\Utils;
use App\Models\Extra;
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
        'hasIva',
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

    public function getSubtotalWithExtrasBeforeCommissionAttribute()
    {
        $subtotal = $this->getSubtotalAttribute();
        $extras = $this->extras;
        foreach ($extras as $extra) {
            if ($extra->type === 'percentage') {
                $subtotal += ($this->getSubtotalAttribute() * ($extra->value / 100));
            } else {
                $subtotal += $extra->value;
            }
        }
        return $subtotal;
    }

    public function getTotalAttribute()
    {
        $subtotalFeeAndExtras = $this->getSubtotalFeeAttribute();
        $ivaAmount = $this->hasIva ? $this->getIvaAmountAttribute() : 0;

        return $subtotalFeeAndExtras + $ivaAmount;
    }

    public function getBalanceAttribute()
    {
        $total = $this->getTotalAttribute();
        $totalIncomes = $this->getAmountPaidAttribute();

        return $total - $totalIncomes;
    }

    public function getFeeAmountAttribute()
    {
        if ($this->fee > 0) {
            $fee = $this->fee / 100;
            // Fee is computed over subtotal base ONLY
            $amountFee = $this->getSubtotalAttribute() * $fee;
            return $amountFee;
        } else {
            return 0;
        }
    }

    public function getSubtotalFeeAttribute()
    {
        // Subtotal + Fee + Extras
        $subtotal = $this->getSubtotalAttribute();
        $fee = $this->getFeeAmountAttribute();
        
        $extrasVal = 0;
        foreach ($this->extras as $extra) {
            if ($extra->type === 'percentage') {
                $extrasVal += ($this->getSubtotalAttribute() * ($extra->value / 100));
            } else {
                $extrasVal += $extra->value;
            }
        }
        
        return $subtotal + $fee + $extrasVal;
    }

    public function getIvaAmountAttribute()
    {
        if ($this->iva > 0) {
            $iva = $this->iva / 100;
            // IVA is calculated over (Subtotal base + Fee + Extras)
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

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function fabrics()
    {
        return $this->hasMany(Fabric::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
    public function outcomes()
    {
        return $this->hasMany(Outcome::class);
    }
    public function extras()
    {
        return $this->hasMany(Extra::class);
    }
}
