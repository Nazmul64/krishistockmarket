<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UiConfiguration extends Model
{
    use HasFactory;

    protected $table = 'ui_configurations';

    protected $fillable = [
        'version_code',
        'status',
        'active_template_slug',
        'global_colors',
        'typography',
        'header_config',
        'footer_config',
        'dark_mode_config',
        'mobile_app_config',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'global_colors' => 'array',
        'typography' => 'array',
        'header_config' => 'array',
        'footer_config' => 'array',
        'dark_mode_config' => 'array',
        'mobile_app_config' => 'array',
        'published_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(DesignTemplate::class, 'active_template_slug', 'slug');
    }

    public static function getActivePublished()
    {
        return static::where('status', 'published')->latest('published_at')->first()
            ?: static::latest('id')->first();
    }
}
