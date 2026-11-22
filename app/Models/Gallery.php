<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';

    protected $fillable = [
        'title_en',
        'title_bn',
        'image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Helper to get title based on current locale
     */
    public function getTitleAttribute()
    {
        $locale = session('locale', 'bn');
        if ($locale === 'en') {
            return $this->title_en ?: ($this->title_bn ?: '');
        }
        return $this->title_bn ?: ($this->title_en ?: '');
    }

    /**
     * Scope for active items
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
