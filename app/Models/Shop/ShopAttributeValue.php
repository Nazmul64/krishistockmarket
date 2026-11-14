<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAttributeValue extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_attribute_values';
    protected $guarded = [];

    public function attribute()
    {
        return $this->belongsTo(ShopAttribute::class, 'attribute_id');
    }
}

