<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\ShopProduct;

class InventoryBatch extends Model
{
    use HasFactory;

    protected $table = 'inventory_batches';

    protected $fillable = [
        'branch_id',
        'product_id',
        'batch_number',
        'mfg_date',
        'expiry_date',
        'initial_qty',
        'current_qty',
        'purchase_price',
        'supplier_id',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && now()->toDateString() > $this->expiry_date;
    }

    public function getIsNearExpiryAttribute()
    {
        return $this->expiry_date && now()->diffInDays($this->expiry_date, false) <= 15 && now()->diffInDays($this->expiry_date, false) >= 0;
    }
}

