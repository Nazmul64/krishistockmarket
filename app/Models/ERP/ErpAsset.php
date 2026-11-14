<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpAsset extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(ErpAssetCategory::class, 'category_id');
    }

    public function assignments()
    {
        return $this->hasMany(ErpAssetAssignment::class, 'asset_id');
    }

    public function transfers()
    {
        return $this->hasMany(ErpAssetTransfer::class, 'asset_id');
    }

    public function depreciations()
    {
        return $this->hasMany(ErpAssetDepreciation::class, 'asset_id');
    }

    public function maintenances()
    {
        return $this->hasMany(ErpMaintenanceRequest::class, 'asset_id');
    }
}

