<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpGoodsReceivedNote extends Model
{
    protected $guarded = ['id'];

    public function purchaseOrder()
    {
        return $this->belongsTo(ErpPurchaseOrder::class, 'po_id');
    }

    public function supplier()
    {
        return $this->belongsTo(ErpSupplier::class, 'supplier_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(ErpWarehouse::class, 'warehouse_id');
    }
}

