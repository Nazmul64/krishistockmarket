<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\ShopProduct;

class PosSalesReturnItem extends Model
{
    use HasFactory;

    protected $table = 'pos_sales_return_items';

    protected $fillable = [
        'sales_return_id',
        'product_id',
        'quantity',
        'unit_price',
        'refund_amount',
        'condition',
        'restored_to_stock',
    ];

    public function salesReturn()
    {
        return $this->belongsTo(PosSalesReturn::class, 'sales_return_id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }
}

