<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'po_number',
        'supplier_id',
        'destination_type',
        'destination_id',
        'order_date',
        'expected_delivery_date',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'status',
        'notes',
        'created_by',
        'approved_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function grns()
    {
        return $this->hasMany(GoodsReceiptNote::class, 'purchase_order_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

