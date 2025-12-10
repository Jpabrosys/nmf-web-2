<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class Video extends Model
{
    use HasFactory;
    public static $skipUpdatedEvent = false;


    protected $fillable = [
        'title',
        'description',
        'eng_name',
        'site_url',
        'author_id',
        'category_id',
        'video_path',
        'thumbnail_path',
        'state_id',
        'keywords',
        'file_size',
        'duration',
        'format',
        'is_active',
        'views',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views' => 'integer',
        'file_size' => 'integer',
        'duration' => 'string',
        'published_at' => 'datetime',
    ];

    
    protected static function booted()
    {
        static::updated(function ($video) {
            if (self::$skipUpdatedEvent) {
                return; // Skip event for view count update
            }

            self::clearPodcastCache($video);
        });

        static::created(function ($video) {
            self::clearPodcastCache($video);
        });

        static::deleted(function ($video) {
            self::clearPodcastCache($video);
        });
    }


    protected static function clearPodcastCache($video)
    {
        if ($video->category_id == 23) {
            Cache::forget('latest_podcast_widget');
        }
    }


    /**
     * Get the category this video belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the author (user) of the video.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
 public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
