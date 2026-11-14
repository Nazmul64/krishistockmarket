<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpStockRequisitionItem extends Model
{
    protected $guarded = ['id'];

    public function requisition()
    {
        return $this->belongsTo(ErpStockRequisition::class, 'requisition_id');
    }

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_id');
    }
}

