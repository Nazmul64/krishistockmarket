<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpGeneralLedgerEntry extends Model
{
    protected $guarded = ['id'];

    public function account()
    {
        return $this->belongsTo(ErpChartOfAccount::class, 'account_id');
    }

    public function voucher()
    {
        return $this->belongsTo(ErpVoucher::class, 'voucher_id');
    }
}

