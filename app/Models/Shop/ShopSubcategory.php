<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSubcategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(ShopCategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(ShopProduct::class, 'subcategory_id')->where('is_active', true);
    }
}

