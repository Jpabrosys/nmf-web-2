<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Comment;
use App\Models\ViewerComment;
use Illuminate\Support\Facades\Cache;

class Blog extends Model
{
    use HasFactory;
    public static $skipUpdatedEvent = false;
    // NL1026:16Sep2025: Cache blog category and detail pages:Start
    protected static function booted()
    {
        static::created(function ($blog) {
            self::clearCategoryCache($blog);
            self::clearBlogCache($blog);
            self::clearBreakingNewsCache($blog);
            self::clearSidebarCache($blog);
            self::clearPodcastCache($blog);
        });

        static::updated(function ($blog) {
            if (self::$skipUpdatedEvent) {
                return; // 🚀 Skip clearing cache + skip touching updated_at
            }

            self::clearCategoryCache($blog);
            self::clearBlogCache($blog);
            self::clearBreakingNewsCache($blog);
            self::clearSidebarCache($blog);
            self::clearPodcastCache($blog);
        });


        static::deleted(function ($blog) {
            self::clearCategoryCache($blog);
            self::clearBlogCache($blog);
            self::clearBreakingNewsCache($blog);
            self::clearSidebarCache($blog);
            self::clearPodcastCache($blog);
        });
    }

    /**
     * Clear category cache(s) when a blog changes.
     */
    private static function clearCategoryCache($blog)
    {
        // Main category
        if (!empty($blog->categories_ids)) {
            $category = Category::find($blog->categories_ids);
            if ($category) {
                Cache::forget("category_page_{$category->site_url}");
            }
        }

        // Multi categories (comma separated)
        if (!empty($blog->mult_cat)) {
            $multiCatIds = explode(',', $blog->mult_cat);
            foreach ($multiCatIds as $catId) {
                $category = Category::find((int) $catId);
                if ($category) {
                    Cache::forget("category_page_{$category->site_url}");
                }
            }
        }
    }

    /**
     * Clear blog detail cache when a blog changes.
     */
    private static function clearBlogCache($blog)
    {
        if ($blog->category) {
            Cache::forget("blog_details_{$blog->category->site_url}_{$blog->site_url}");
        }
    }

    /**
     * Clear breaking news cache when a blog changes.
     */
    private static function clearBreakingNewsCache($blog)
    {
        if ($blog->breaking_status == 1) {
            Cache::forget("breaking_news");
        }
    }

    /**
     * Clear sidebar cache when relevant blog changes.
     */
    private static function clearSidebarCache($blog)
    {
        // Sidebar categories list
        $sidebarCategories = [
            'क्या कहता है कानून?',
            'पॉडकास्ट',
            'टेक्नोलॉजी',
            'स्पेशल्स',
        ];

        // If blog’s category matches sidebar categories → clear the whole sidebar cache
        $category = $blog->category ?? null;
        if ($category && in_array($category->name, $sidebarCategories)) {
            Cache::forget('sidebar_widgets');
        }
    }

    protected static function clearPodcastCache($blog)
    {
        if ($blog->categories_ids == 23) {
            Cache::forget('latest_podcast_widget');
        }
    }
    // NL1026:16Sep2025: Cache blog category and detail pages:End


    // comments realted methods
    public function comments()
    {
        return $this->morphMany(ViewerComment::class, 'commentable')
            ->whereNull('parent_id')
            ->with(['replies.viewer', 'viewer'])
            ->latest();
    }

    public function allComments()
    {
        return $this->morphMany(ViewerComment::class, 'commentable');
    }

    public function commentsCount()
    {
        return $this->allComments()->count();
    }

    protected $guarded = ['id'];
    protected $table = 'blogs';

    // Status Constants
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_SCHEDULED = 4;

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_ids');
    }

    // Relationship with Author (User model)
    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }

    // Relationship with Images
    public function images()
    {
        return $this->belongsTo(File::class, 'image_ids');
    }

    // Relationship with Thumbnail
    public function thumbnail()
    {
        return $this->belongsTo(File::class, 'thumb_images');
    }

    // Relationship with Sub Category
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    // Scope to join with page_sequences
    public function scopeJoinSequence($query)
    {
        $query->rightJoin('page_sequences', 'page_sequences.blog_id', '=', 'blogs.id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_ids');
    }

    // Accessor for status label
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_UNPUBLISHED => 'Unpublished',
            self::STATUS_ARCHIVED => 'Archived',
            self::STATUS_SCHEDULED => 'Scheduled',
            default => 'Unknown',
        };
    }

    public static function determineStatusFromSchedule($request): int
    {
        $publishedAt = $request->scheduletime;

        if (isset($request->publish)) {
            $publishedDate = Carbon::parse($publishedAt);
            return $publishedDate->isFuture() ? self::STATUS_SCHEDULED : self::STATUS_PUBLISHED;
        } elseif (isset($request->unpublish)) {
            return self::STATUS_UNPUBLISHED;
        } elseif (isset($request->draft)) {
            return self::STATUS_DRAFT;
        }

        return self::STATUS_ARCHIVED;
    }

    // Blog.php model
    public function getFullUrlAttribute()
    {
        $slug = $this->category->site_url ?? '';
        if ($this->isLive) {
            return $this->site_url ? asset("live/{$slug}/{$this->site_url}") : '#';
        }
        return $this->site_url ? asset("{$slug}/{$this->site_url}") : '#';
    }

}
