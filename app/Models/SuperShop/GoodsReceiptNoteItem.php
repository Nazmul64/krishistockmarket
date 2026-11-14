<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\ShopProduct;

class GoodsReceiptNoteItem extends Model
{
    use HasFactory;

    protected $table = 'goods_receipt_note_items';

    protected $fillable = [
        'grn_id',
        'product_id',
        'ordered_qty',
        'received_qty',
        'accepted_qty',
        'damaged_qty',
        'rejected_qty',
        'batch_number',
        'expiry_date',
    ];

    public function grn()
    {
        return $this->belongsTo(GoodsReceiptNote::class, 'grn_id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }
}

