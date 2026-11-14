<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductVariant extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_product_variants';
    protected $guarded = [];

    protected $casts = [
        'attribute_values' => 'array',
        'is_active' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function getVariantNameAttribute()
    {
        if (empty($this->attribute_values)) {
            return $this->sku ?: 'Default Variant';
        }
        $parts = [];
        foreach ($this->attribute_values as $k => $v) {
            $parts[] = "{$k}: {$v}";
        }
        return implode(' / ', $parts);
    }
}

