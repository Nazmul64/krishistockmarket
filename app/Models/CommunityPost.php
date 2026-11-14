<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'content',
        'image',
        'likes_count',
        'comments_count',
        'is_pinned',
        'status',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(CommunityComment::class, 'post_id')->latest();
    }
}

