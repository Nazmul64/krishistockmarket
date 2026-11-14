<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpStockAdjustment extends Model
{
    protected $guarded = ['id'];

    public function warehouse()
    {
        return $this->belongsTo(ErpWarehouse::class, 'warehouse_id');
    }

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_id');
    }
}

