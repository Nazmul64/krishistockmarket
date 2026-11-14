<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpVehicleDocument extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(ErpVehicle::class, 'vehicle_id');
    }

    public function isExpired()
    {
        return $this->expiry_date < now()->toDateString();
    }

    public function isExpiringSoon()
    {
        $alertDate = now()->addDays($this->alert_days)->toDateString();
        return !$this->isExpired() && $this->expiry_date <= $alertDate;
    }
}

