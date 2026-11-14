<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpWarehouse extends Model
{
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(ErpItem::class, 'default_warehouse_id');
    }
}

