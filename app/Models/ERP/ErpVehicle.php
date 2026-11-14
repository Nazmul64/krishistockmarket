<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpVehicle extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function documents()
    {
        return $this->hasMany(ErpVehicleDocument::class, 'vehicle_id');
    }

    public function trips()
    {
        return $this->hasMany(ErpVehicleTrip::class, 'vehicle_id');
    }

    public function fuels()
    {
        return $this->hasMany(ErpVehicleFuel::class, 'vehicle_id');
    }

    public function maintenances()
    {
        return $this->hasMany(ErpMaintenanceRequest::class, 'vehicle_id');
    }
}

