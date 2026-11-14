<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpStockTransferItem extends Model
{
    protected $guarded = ['id'];

    public function transfer()
    {
        return $this->belongsTo(ErpStockTransfer::class, 'transfer_id');
    }

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_id');
    }
}

