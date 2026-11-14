<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpAssetDisposal extends Model
{
    protected $guarded = ['id'];

    public function asset()
    {
        return $this->belongsTo(ErpAsset::class, 'asset_id');
    }
}

