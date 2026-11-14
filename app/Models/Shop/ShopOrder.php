<?php

namespace App\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status_history' => 'array',
        'subtotal' => 'float',
        'shipping_charge' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(ShopOrderItem::class, 'order_id');
    }

    public function shippingZone()
    {
        return $this->belongsTo(ShopShippingZone::class, 'shipping_zone_id');
    }

    public function addStatusHistory($status, $comment = '')
    {
        $history = $this->status_history ?: [];
        $history[] = [
            'status' => $status,
            'comment' => $comment,
            'changed_at' => now()->toDateTimeString(),
            'changed_by' => auth()->user() ? auth()->user()->name : 'System'
        ];
        $this->status_history = $history;
        $this->order_status = $status;
        $this->save();
    }
}
