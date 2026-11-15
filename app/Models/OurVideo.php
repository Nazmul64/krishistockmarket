<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurVideo extends Model
{
    use HasFactory;

    protected $table = 'our_videos';

    protected $fillable = [
        'title',
        'video_url',
        'description',
        'button_text',
        'button_link',
        'order_num',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_num' => 'integer',
    ];

    /**
     * Determine if the video URL is a direct video file (mp4, webm, ogg).
     */
    public function getIsDirectVideoAttribute(): bool
    {
        $url = trim((string)$this->video_url);
        $cleanUrl = strtok($url, '?');
        $ext = strtolower(pathinfo($cleanUrl, PATHINFO_EXTENSION));
        return in_array($ext, ['mp4', 'webm', 'ogg', 'mov']);
    }

    /**
     * Extract the YouTube Video ID from any YouTube URL variation.
     */
    public function getYoutubeIdAttribute(): ?string
    {
        $url = trim((string)$this->video_url);

        // Pattern covers watch?v=, youtu.be/, embed/, shorts/, and v/
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';
        if (preg_match($pattern, $url, $match)) {
            return $match[1];
        }

        return null;
    }

    /**
     * Generate responsive Embed URL for iframe or direct player.
     */
    public function getEmbedUrlAttribute(): string
    {
        $ytId = $this->youtube_id;
        if ($ytId) {
            return "https://www.youtube.com/embed/{$ytId}?rel=0&modestbranding=1";
        }

        return (string)$this->video_url;
    }

    /**
     * Get thumbnail URL (HQ YouTube thumbnail or default placeholder).
     */
    public function getThumbnailUrlAttribute(): string
    {
        $ytId = $this->youtube_id;
        if ($ytId) {
            return "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg";
        }

        return asset('assets/images/placeholder.png');
    }
}
