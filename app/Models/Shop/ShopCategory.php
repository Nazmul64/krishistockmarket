<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subcategories()
    {
        return $this->hasMany(ShopSubcategory::class, 'category_id')->orderBy('order_num', 'asc');
    }

    public function products()
    {
        return $this->hasMany(ShopProduct::class, 'category_id')->where('is_active', true);
    }
}

