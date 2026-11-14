<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopComboOffer extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_combo_offers';
    protected $guarded = [];

    protected $casts = [
        'product_ids' => 'array'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
            });
    }
}
