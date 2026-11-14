<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCoupon extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_coupons';
    protected $guarded = [];

    public function isValid($cartSubtotal = 0)
    {
        if (!$this->is_active) return false;
        if ($this->start_date && now()->toDateString() < $this->start_date) return false;
        if ($this->end_date && now()->toDateString() > $this->end_date) return false;
        if ($this->usage_limit > 0 && $this->used_count >= $this->usage_limit) return false;
        if ($cartSubtotal < $this->min_order_amount) return false;
        return true;
    }

    public function calculateDiscount($subtotal)
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($subtotal * $this->discount_amount) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return $discount;
        }
        return min($this->discount_amount, $subtotal);
    }
}
