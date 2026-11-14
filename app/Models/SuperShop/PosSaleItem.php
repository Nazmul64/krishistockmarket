<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\ShopProduct;

class PosSaleItem extends Model
{
    use HasFactory;

    protected $table = 'pos_sale_items';

    protected $fillable = [
        'pos_sale_id',
        'product_id',
        'product_name',
        'sku',
        'barcode',
        'quantity',
        'unit_price',
        'cost_price',
        'discount',
        'tax',
        'total_price',
    ];

    public function sale()
    {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function getGrossProfitAttribute()
    {
        return $this->total_price - ($this->cost_price * $this->quantity);
    }
}

