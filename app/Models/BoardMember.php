<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_bn',
        'designation',
        'designation_bn',
        'role_type',
        'photo',
        'bio',
        'administrative_role',
        'phone',
        'email',
        'speech_title',
        'speech_message',
        'order_num',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_num' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_num', 'asc');
    }

    public function scopeLeadershipMessages($query)
    {
        return $query->where('is_active', true)
            ->whereNotNull('speech_message')
            ->where('speech_message', '!=', '')
            ->orderBy('order_num', 'asc');
    }
}

