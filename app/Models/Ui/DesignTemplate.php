<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DesignTemplate extends Model
{
    use HasFactory;

    protected $table = 'design_templates';

    protected $fillable = [
        'slug',
        'name_en',
        'name_bn',
        'category',
        'preview_thumbnail',
        'description_en',
        'description_bn',
        'default_config',
        'is_active',
    ];

    protected $casts = [
        'default_config' => 'array',
        'is_active' => 'boolean',
    ];
}
