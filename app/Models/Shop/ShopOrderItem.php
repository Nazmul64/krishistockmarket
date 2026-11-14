<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'total' => 'float',
        'quantity' => 'integer'
    ];

    public function order()
    {
        return $this->belongsTo(ShopOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }
}
