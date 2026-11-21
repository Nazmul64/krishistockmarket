<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemeChangeLog extends Model
{
    use HasFactory;

    protected $table = 'theme_change_logs';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'diff_payload',
        'ip_address',
    ];

    protected $casts = [
        'diff_payload' => 'array',
    ];
}
