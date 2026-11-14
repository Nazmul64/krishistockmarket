<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpSupplier extends Model
{
    protected $guarded = ['id'];

    public function purchaseOrders()
    {
        return $this->hasMany(ErpPurchaseOrder::class, 'supplier_id');
    }
}

