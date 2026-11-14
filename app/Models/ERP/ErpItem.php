<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpItem extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(ErpItemCategory::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(ErpItemSubcategory::class, 'subcategory_id');
    }

    public function unit()
    {
        return $this->belongsTo(ErpItemUnit::class, 'unit_id');
    }

    public function defaultWarehouse()
    {
        return $this->belongsTo(ErpWarehouse::class, 'default_warehouse_id');
    }

    public function transactions()
    {
        return $this->hasMany(ErpStockTransaction::class, 'item_id');
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->reorder_level;
    }
}

