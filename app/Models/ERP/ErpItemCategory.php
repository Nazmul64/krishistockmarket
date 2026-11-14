<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpItemCategory extends Model
{
    protected $guarded = ['id'];

    public function subcategories()
    {
        return $this->hasMany(ErpItemSubcategory::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(ErpItem::class, 'category_id');
    }
}

