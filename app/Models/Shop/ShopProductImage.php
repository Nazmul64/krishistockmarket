<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductImage extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_product_images';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }
}
