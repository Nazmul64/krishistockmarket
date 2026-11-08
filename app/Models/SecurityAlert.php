<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'severity',
        'source_ip',
        'target',
        'status',
        'details',
        'resolved_by',
        'resolved_at',
    ];

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
