<?php

namespace App\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReview extends Model
{
    use HasFactory;

    protected $table = 'ecommerce_product_reviews';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

