<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpStockRequisition extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'requisition_date' => 'date',
        'needed_by_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(ErpStockRequisitionItem::class, 'requisition_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(ErpWarehouse::class, 'warehouse_id');
    }
}

