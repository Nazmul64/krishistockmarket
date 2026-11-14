<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpPurchaseOrder extends Model
{
    protected $guarded = ['id'];

    public function supplier()
    {
        return $this->belongsTo(ErpSupplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(ErpPurchaseOrderItem::class, 'po_id');
    }

    public function grns()
    {
        return $this->hasMany(ErpGoodsReceivedNote::class, 'po_id');
    }
}

