<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopBanner extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_banners';
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_num', 'asc');
    }
}

