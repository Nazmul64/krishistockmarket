<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\ShopProduct;

class StockTransferItem extends Model
{
    use HasFactory;

    protected $table = 'stock_transfer_items';

    protected $fillable = [
        'transfer_id',
        'product_id',
        'requested_qty',
        'dispatched_qty',
        'received_qty',
        'damaged_qty',
        'notes',
    ];

    public function transfer()
    {
        return $this->belongsTo(StockTransfer::class, 'transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }
}

