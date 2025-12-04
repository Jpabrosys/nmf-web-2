<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use App\Models\Blog;
use App\Models\WebStories;
use App\Models\Video;
use App\Models\Clip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 

class SitemapController extends Controller
{
    // -------------------------------------------------------------------------
    // STRICT QUALITY SETTINGS
    // -------------------------------------------------------------------------
    // Minimum characters in the article body to be considered "valuable"
    private $minCharCount = 400; 
    
    // Minimum characters in title (avoids "Hi", "Test", "Update")
    private $minTitleLength = 15; 

    public function index()
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0'],
            ['loc' => url('/about'), 'priority' => '0.8'],
            ['loc' => url('/contact'), 'priority' => '0.8'],
            ['loc' => url('/privacy'), 'priority' => '0.8'],
            ['loc' => url('/disclaimer'), 'priority' => '0.8'],
        ];

        // Categories
        $categories = Category::where('home_page_status', '1')->get();
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => url('/' . $category->site_url),
                'priority' => '0.8',
            ];
        }

        // States
        $states = State::where('home_page_status', '1')->get();
        foreach ($states as $state) {
            $urls[] = [
                'loc' => url('/state/' . $state->site_url),
                'priority' => '0.8',
            ];
        }

        return response()->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

   public function newsSitemap()
    {
        // GOOGLE NEWS: Strict 48-hour window
        $blogs = Blog::where('status', 1)
            ->where('created_at', '>=', now()->subDays(2)) // CHANGED: 100 -> 2
            ->where('created_at', '<=', now())
            
            // Description Length Check
            ->whereRaw('CHAR_LENGTH(description) >= ?', [$this->minCharCount])
            
            // Title Length Check
            ->whereRaw('CHAR_LENGTH(name) >= ?', [$this->minTitleLength])
            
            // Exclude Test/Demo content
            ->where('name', 'NOT LIKE', '%test%')
            ->where('name', 'NOT LIKE', '%demo%')

            // Relationships - Ensure Category exists to avoid broken links
            ->whereHas('category')
            ->with('category')
            
            ->orderBy('created_at', 'desc')
            ->take(1000) // Increased limit slightly for big news days
            ->get();

        return response()->view('news-sitemap', compact('blogs'))
            ->header('Content-Type', 'application/xml');
    }

    public function webstoriesSitemap()
    {
        $urls = [];

        $webStories = WebStories::where('status', 1)
            ->whereHas('category')
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($webStories as $story) {
            if (!$story->category) continue;

            $urls[] = [
                'loc' => url('/web-stories/' . $story->category->site_url . '/' . $story->siteurl),
                'lastmod' => $story->updated_at,
                'priority' => '0.5',
            ];
        }

        return response()->view('webstories-sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    public function sitemapIndex()
    {
        $sitemaps = [];

        // OPTIMIZATION: Match filters exactly with dailySitemap to prevent empty map errors
        $dates = Blog::select(DB::raw('DATE(created_at) as date'))
            ->where('status', 1)
            ->where('created_at', '>=', now()->subDays(100))
            
            // Content Quality Checks
            ->whereRaw('CHAR_LENGTH(description) >= ?', [$this->minCharCount])
            ->whereRaw('CHAR_LENGTH(name) >= ?', [$this->minTitleLength])
            
            // Exclude Test Data
            ->where('name', 'NOT LIKE', '%test%')
            ->where('name', 'NOT LIKE', '%demo%')

            // Ensure relationships exist
            ->whereHas('category')
            
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date');

        foreach ($dates as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $sitemaps[] = [
                'loc' => url("sitemap/generic-articles-$formattedDate.xml"),
            ];
        }

        return response()->view('articles-sitemap', compact('sitemaps'))
            ->header('Content-Type', 'application/xml');
    }


    public function dailySitemap($date)
    {
        // 1. Validate Date Format
        try {
            $parsedDate = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Exception $e) {
            abort(404); // Return 404 for invalid dates
        }

        $blogs = Blog::whereDate('created_at', $parsedDate)
            ->where('status', 1)
            
            // Quality & Spam Filters
            ->whereRaw('CHAR_LENGTH(description) >= ?', [$this->minCharCount])
            ->whereRaw('CHAR_LENGTH(name) >= ?', [$this->minTitleLength])
            ->where('name', 'NOT LIKE', '%test%')
            ->where('name', 'NOT LIKE', '%demo%')

            // Relationship Checks
            ->whereHas('category')
            
            ->with('category')
            ->get();

        // 2. Prevent Empty XML responses
        if ($blogs->isEmpty()) {
            abort(404);
        }

        return response()->view('news-sitemap', compact('blogs'))
            ->header('Content-Type', 'application/xml');
    }

    public function videoSitemap()
    {
        $urls = [];

        $videos = Video::where('is_active', 1)
            ->whereHas('category')
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        foreach ($videos as $video) {
            if (!$video->category) continue;

            $durationInSeconds = $this->parseDurationToSeconds($video->duration);
            
            $cleanDesc = strip_tags($video->description);
            if(empty($cleanDesc)) $cleanDesc = $video->title;

            $urls[] = [
                'loc' => url('/videos/' . $video->category->site_url . '/' . $video->site_url),
                'lastmod' => $video->updated_at,
                'thumbnail' => url($video->thumbnail_path),
                'title' => $video->title,
                'description' => $cleanDesc,
                'content' => url($video->video_path),
                'duration' => $durationInSeconds,
                'publication_date' => ($video->published_at ?? $video->created_at)->toAtomString(),
                'category' => $video->category->name,
                'uploader' => 'newsnmf.com',
            ];
        }

        return response()
            ->view('video-sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    public function reelVideoSitemap()
    {
        $urls = [];

        $clips = Clip::where('status', 1)
            ->whereHas('category')
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        foreach ($clips as $clip) {
            if (!$clip->category) continue;

            $durationInSeconds = $this->parseDurationToSeconds($clip->duration);

            // 1. Get the raw paths from DB
            // Example DB Value: /var/www/html/newsnmf.com/public/file/shortvideos/clip.mp4
            $rawVideoPath = rtrim($clip->video_path, '/') . '/' . $clip->clip_file_name;
            $rawThumbPath = $clip->thumb_image;

            // 2. CLEAN THE PATHS
            // This splits the string at "/public/" and takes the part AFTER it.
            // Input: /var/www/html/newsnmf.com/public/file/shortvideos...
            // Output: file/shortvideos...
            $cleanVideoPath = $this->stripServerPath($rawVideoPath);
            $cleanThumbPath = $this->stripServerPath($rawThumbPath);

            // 3. Generate Correct URLs
            // url() adds http://domain.com + / + clean path
            $finalVideoUrl = url($cleanVideoPath);
            $finalThumbUrl = url($cleanThumbPath);

            $cleanDesc = strip_tags($clip->description);
            if(empty($cleanDesc)) $cleanDesc = $clip->title;

            $urls[] = [
                'loc' => url('/reels/' . $clip->category->site_url . '/' . $clip->site_url),
                'lastmod' => $clip->updated_at,
                'thumbnail' => $finalThumbUrl, // FIXED
                'title' => $clip->title,
                'description' => $cleanDesc,
                'content' => $finalVideoUrl, // FIXED
                'duration' => $durationInSeconds,
                'publication_date' => $clip->created_at->toAtomString(),
                'category' => $clip->category->name,
                'uploader' => "newsnmf.com"
            ];
        }

        return response()
            ->view('video-sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Removes everything before and including "/public/" from a path.
     */
    private function stripServerPath($fullPath)
    {
        if (empty($fullPath)) return '';

        // Normalize slashes (just in case windows/linux mix)
        $fullPath = str_replace('\\', '/', $fullPath);

        // Check if path contains '/public/'
        if (strpos($fullPath, '/public/') !== false) {
            $parts = explode('/public/', $fullPath, 2);
            return ltrim($parts[1], '/'); // Return the part AFTER public
        }

        // Fallback: Try standard public_path removal if '/public/' isn't found explicitly
        $sysPath = str_replace('\\', '/', public_path());
        $relativePath = str_replace($sysPath, '', $fullPath);
        
        return ltrim($relativePath, '/');
    }

    private function parseDurationToSeconds($duration)
    {
        if (empty($duration) || !is_string($duration)) {
            return null;
        }

        $parts = explode(':', $duration);
        $seconds = 0;

        try {
            if (count($parts) === 3) {
                $seconds = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
            } elseif (count($parts) === 2) {
                $seconds = ((int)$parts[0] * 60) + (int)$parts[1];
            } elseif (count($parts) === 1 && is_numeric($parts[0])) {
                $seconds = (int)$parts[0];
            }
        } catch (\Exception $e) {
            return null;
        }
        
        return $seconds > 0 ? $seconds : null;
    }
}