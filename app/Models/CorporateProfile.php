<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'title_bn',
        'title_en',
        'content_bn',
        'content_en',
        'featured_image',
        'extra_points',
        'is_active',
    ];

    protected $casts = [
        'extra_points' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getSection($key)
    {
        return self::where('section_key', $key)->where('is_active', true)->first();
    }
}

