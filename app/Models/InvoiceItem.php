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

use App\Models\Label;

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
        'discount',
        'discount_type',
        'order',
        'item_label_id',
    ];

    protected $casts = [
        'discount' => 'float',
    ];
    use HasFactory;

    public function itemLabel()
    {
        return $this->belongsTo(Label::class, 'item_label_id');
    }

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

    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = is_numeric($value) ? $value : 0.00;
    }

    public function setDiscountTypeAttribute($value)
    {
        $this->attributes['discount_type'] = !empty($value) ? $value : 'percentage';
    }

    public function getDiscountAmountAttribute()
    {
        $gross = $this->unit_price * $this->units;
        if ($this->discount > 0) {
            if ($this->discount_type === 'fixed') {
                return min($this->discount, $gross);
            } else {
                return $gross * ($this->discount / 100);
            }
        }
        return 0;
    }

    public function getTotalProfitAttribute()
    {
        $totalCost = $this->unit_cost * $this->units;
        $totalRevenue = $this->getTotalAttribute();
        return $totalRevenue - $totalCost;
    }

    public function getPercentageProfitAttribute()
    {
        $totalCost = $this->unit_cost * $this->units;
        if ($totalCost == 0) {
            return 0;
        }
        $profit = $this->getTotalProfitAttribute();
        return ($profit / $totalCost) * 100;
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
        $gross = ($this->unit_price * $this->units);
        return max(0, $gross - $this->getDiscountAmountAttribute());
    }

    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    public function getAmountAttribute()
    {
        return $this->getTotalAttribute();
    }

    public function getFormatDateAttribute()
    {
        return Utils::formatDate($this->created_at);
    }
}
