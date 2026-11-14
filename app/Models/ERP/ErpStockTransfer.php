<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpStockTransfer extends Model
{
    protected $guarded = ['id'];

    public function fromWarehouse()
    {
        return $this->belongsTo(ErpWarehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(ErpWarehouse::class, 'to_warehouse_id');
    }

    public function items()
    {
        return $this->hasMany(ErpStockTransferItem::class, 'transfer_id');
    }
}

