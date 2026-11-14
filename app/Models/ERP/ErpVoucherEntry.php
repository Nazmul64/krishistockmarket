<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpVoucherEntry extends Model
{
    protected $guarded = ['id'];

    public function voucher()
    {
        return $this->belongsTo(ErpVoucher::class, 'voucher_id');
    }

    public function account()
    {
        return $this->belongsTo(ErpChartOfAccount::class, 'account_id');
    }
}

