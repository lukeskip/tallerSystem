<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Utils;
use App\Models\Provider;
use App\Models\Invoice;
use App\Models\Note;
use App\Models\Category;
use App\Models\User;

class InvoiceItem extends Model
{
    protected $fillable = [
        'label',
        'description',
        'amount',
        'comission',
        'provider_id',
        'category_id',
        'invoice_id',
        'user_id',
        'units',
        'unit_price',
        'unit_cost',
        'unit_type',
    ];
    use HasFactory;

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'invoice_item_file');
    }

    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, "invoice_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setUnitCostAttribute($value)
    {
        $this->attributes['unit_cost'] = is_numeric($value) ? $value : 0.00;
    }

    public function getTotalProfitAttribute()
    {
        $unitCost = $this->unit_cost;
        $unitPrice = $this->unit_price;
        $units = $this->units;
        $total = ($unitPrice - $unitCost) * $units;
        return $total;
    }

    public function getPercentageProfitAttribute()
    {
        if ($this->unit_cost == 0) {
            return 0;
        }
        $unitCost = $this->unit_cost;
        $unitPrice = $this->unit_price;
        $total = $unitPrice - $unitCost;
        $percentage = ($total / $unitCost) * 100;
        return $percentage;
    }

    public function getAgentComissionAttribute()
    {
        if ($this->user) {
            $comission = $this->total_profit * $this->invoice->agent_comission / 100;
            if ($comission < 0) {
                $comission = 0;
            }
        } else {
            $comission = 0;
        }

        return $comission;
    }

    public function getTotalAttribute()
    {
        $total = ($this->unit_price * $this->units);
        return $total;
    }
    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    public function getAmountAttribute()
    {
        $total = ($this->unit_price * $this->units);
        return $total;
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
