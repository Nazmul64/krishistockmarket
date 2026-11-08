<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blocklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'reason',
        'blocked_by',
        'expires_at',
        'status',
    ];

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }
}
