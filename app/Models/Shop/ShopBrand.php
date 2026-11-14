<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopBrand extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_brands';
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(ShopProduct::class, 'brand_id');
    }
}

