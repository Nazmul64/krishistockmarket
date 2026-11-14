<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAttribute extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_attributes';
    protected $guarded = [];

    public function values()
    {
        return $this->hasMany(ShopAttributeValue::class, 'attribute_id')->orderBy('order_num', 'asc');
    }
}

