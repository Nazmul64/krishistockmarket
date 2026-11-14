<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpDocument extends Model
{
    protected $guarded = ['id'];

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now()->toDateString();
    }

    public function isExpiringSoon()
    {
        if (!$this->expiry_date) return false;
        $alertDate = now()->addDays($this->alert_days)->toDateString();
        return !$this->isExpired() && $this->expiry_date <= $alertDate;
    }
}

