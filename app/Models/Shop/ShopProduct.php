<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'has_variants' => 'boolean',
        'is_exclusive' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_trending' => 'boolean',
        'is_popular' => 'boolean',
        'is_best_selling' => 'boolean',
        'is_daily_offer' => 'boolean',
        'is_combo_offer' => 'boolean',
        'is_winter_essential' => 'boolean',
        'is_monthly_bazar' => 'boolean',
        'offer_end_date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ShopCategory::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(ShopSubcategory::class, 'subcategory_id');
    }

    public function brand()
    {
        return $this->belongsTo(ShopBrand::class, 'brand_id');
    }

    public function variants()
    {
        return $this->hasMany(ShopProductVariant::class, 'product_id')->where('is_active', true);
    }

    public function images()
    {
        return $this->hasMany(ShopProductImage::class, 'product_id')->orderBy('order_num', 'asc');
    }

    public function reviews()
    {
        return $this->hasMany(ShopReview::class, 'product_id')->where('is_approved', true);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0) . '৳';
    }

    public function getFormattedOldPriceAttribute()
    {
        return $this->old_price ? number_format($this->old_price, 0) . '৳' : null;
    }

    public function getDiscountSavingsAttribute()
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return round($this->old_price - $this->price);
        }
        return 0;
    }
}
