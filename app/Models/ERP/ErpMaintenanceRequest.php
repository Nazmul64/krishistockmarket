<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpMaintenanceRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'request_date' => 'date',
        'completion_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(ErpAsset::class, 'asset_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(ErpVehicle::class, 'vehicle_id');
    }
}

