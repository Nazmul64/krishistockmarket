<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpVehicleTrip extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'trip_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(ErpVehicle::class, 'vehicle_id');
    }
}

