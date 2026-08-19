<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPoint extends Model
{
    use HasFactory;

    protected $table = 'agent_points';

    protected $fillable = [
        'name',
        'area',
        'address',
        'contact_number',
        'status',
    ];
}
