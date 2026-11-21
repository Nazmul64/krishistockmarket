<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomepageSection extends Model
{
    use HasFactory;

    protected $table = 'homepage_sections';

    protected $fillable = [
        'section_key',
        'title_bn',
        'title_en',
        'subtitle_bn',
        'subtitle_en',
        'component_name',
        'sort_order_web',
        'sort_order_app',
        'is_enabled_web',
        'is_enabled_app',
        'section_settings',
    ];

    protected $casts = [
        'sort_order_web' => 'integer',
        'sort_order_app' => 'integer',
        'is_enabled_web' => 'boolean',
        'is_enabled_app' => 'boolean',
        'section_settings' => 'array',
    ];
}
