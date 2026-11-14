<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'project_code',
        'category',
        'sector_type',
        'location',
        'progress_percentage',
        'budget',
        'description',
        'field_report',
        'featured_image',
        'gallery_images',
        'video_url',
        'start_date',
        'completion_date',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'progress_percentage' => 'integer',
        'budget' => 'decimal:2',
        'start_date' => 'date',
        'completion_date' => 'date',
    ];

    public function scopeOngoing($query)
    {
        return $query->where('category', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('category', 'completed');
    }

    public function scopePlanned($query)
    {
        return $query->where('category', 'planned');
    }
}

