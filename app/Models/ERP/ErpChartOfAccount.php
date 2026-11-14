<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpChartOfAccount extends Model
{
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(ErpChartOfAccount::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ErpChartOfAccount::class, 'parent_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(ErpGeneralLedgerEntry::class, 'account_id');
    }
}

