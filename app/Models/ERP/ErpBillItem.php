<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpBillItem extends Model
{
    protected $guarded = ['id'];

    public function bill()
    {
        return $this->belongsTo(ErpBill::class, 'bill_id');
    }
}

