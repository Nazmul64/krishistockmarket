<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'resume',
        'cover_letter',
        'position_applied',
        'status',
        'admin_notes',
    ];

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getResumeUrlAttribute()
    {
        if ($this->resume) {
            return asset($this->resume);
        }
        return null;
    }
}

