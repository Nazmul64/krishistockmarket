<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemeVersion extends Model
{
    use HasFactory;

    protected $table = 'theme_versions';

    protected $fillable = [
        'version_number',
        'template_slug',
        'full_config_snapshot',
        'note',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'full_config_snapshot' => 'array',
        'is_active' => 'boolean',
    ];
}
