<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class GoodsReceiptNote extends Model
{
    use HasFactory;

    protected $table = 'goods_receipt_notes';

    protected $fillable = [
        'grn_number',
        'purchase_order_id',
        'supplier_id',
        'destination_type',
        'destination_id',
        'received_date',
        'received_by',
        'verified_by',
        'status',
        'notes',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(GoodsReceiptNoteItem::class, 'grn_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

