<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpStockTransaction extends Model
{
    protected $guarded = ['id'];

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(ErpWarehouse::class, 'warehouse_id');
    }
}

