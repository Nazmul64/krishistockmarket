<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpVehicleFuel extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'fuel_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(ErpVehicle::class, 'vehicle_id');
    }
}

