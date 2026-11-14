<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'notice_type',
        'content',
        'attachment_file',
        'is_pinned',
        'is_active',
        'publish_date',
        'expiry_date',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_active' => 'boolean',
        'publish_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('publish_date', 'desc');
    }
}

