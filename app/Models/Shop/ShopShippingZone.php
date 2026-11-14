<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopShippingZone extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_shipping_zones';
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_num', 'asc');
    }
}

