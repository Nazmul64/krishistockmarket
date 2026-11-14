<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpPurchaseOrderItem extends Model
{
    protected $guarded = ['id'];

    public function purchaseOrder()
    {
        return $this->belongsTo(ErpPurchaseOrder::class, 'po_id');
    }

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_id');
    }
}

