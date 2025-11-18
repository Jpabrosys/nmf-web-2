<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use App\Models\Blog;
use App\Models\WebStories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Clip;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
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
       $blogs = Blog::where('status', 1)
    ->where('created_at', '>=', now()->subDays(100))
    ->whereHas('category') // ensure only blogs with category
    ->with('category')
    ->orderBy('created_at', 'desc')
    ->take(100)
    ->get();

        return response()->view('news-sitemap', compact('blogs'))
                         ->header('Content-Type', 'application/xml');
    }
    public function webstoriesSitemap()
    {
       $urls = [];

    $webStories = WebStories::where('status', 1)
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

        for ($i = 0; $i < 100; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');

            $hasArticles = Blog::whereDate('created_at', $date)
                ->where('status', 1)
                ->whereHas('category') // <-- ensures blog has a category
                ->exists();

            if ($hasArticles) {
                $sitemaps[] = [
                    'loc' => url("sitemap/generic-articles-$date.xml"),
                ];
            }
        }

        return response()->view('articles-sitemap', compact('sitemaps'))
                        ->header('Content-Type', 'application/xml');
    }


    public function dailySitemap($date)
    {
        try {
            $parsedDate = \Carbon\Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            abort(404, 'Invalid date format');
        }

        // Fetch full blog records with categories
        $blogs = \App\Models\Blog::whereDate('created_at', $parsedDate)
            ->where('status', 1)
            ->whereHas('category')
            ->with('category')
            ->get();

        return response()->view('news-sitemap', compact('blogs'))
                        ->header('Content-Type', 'application/xml');
    }
public function videoSitemap()
    {
        $urls = [];

        $videos = Video::where('is_active', 1)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        foreach ($videos as $video) {

            if (!$video->category) continue;

            // Convert "12:42" or "1:05:30" to total seconds
            $durationInSeconds = $this->parseDurationToSeconds($video->duration);

            $urls[] = [
                // --- THIS LINE IS NOW FIXED ---
                'loc' => url('/videos/' . $video->category->site_url . '/' . $video->site_url),
                'lastmod' => $video->updated_at,

                // VIDEO FIELDS
                'thumbnail' => url($video->thumbnail_path),
                'title' => $video->title,
                'description' => strip_tags($video->description),
                'content' => url($video->video_path),
                'duration' => $durationInSeconds, // Use the new converted value
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
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        // --- THIS IS THE EXACT PATH TO REMOVE ---
        $path_to_remove = "/var/www/html/newsnmf.com/public";

        foreach ($clips as $clip) {

            if (!$clip->category) continue;

            // 1. Fixes the DURATION
            $durationInSeconds = $this->parseDurationToSeconds($clip->duration); 

            // 2. Fix the THUMBNAIL URL
            // $clip->thumb_image is "http://nmf.test/var/www/.../image.jpg"
            $correct_thumb_url = str_replace($path_to_remove, '', $clip->thumb_image);
            // $correct_thumb_url is now "http://nmf.test/image.jpg"
            
            // 3. Fix the VIDEO URL
            // A) Combine the path and filename
            $broken_video_url = rtrim($clip->video_path, '/') . '/' . $clip->clip_file_name;
            // $broken_video_url is "http://nmf.test/var/www/.../video.mp4"
            
            // B) Remove the server path
            $correct_video_url = str_replace($path_to_remove, '', $broken_video_url);
            // $correct_video_url is now "http://nmf.test/file/shortvideos/.../video.mp4"

            $urls[] = [
                'loc'         => url('/reels/' . $clip->category->site_url . '/' . $clip->site_url),
                'lastmod'     => $clip->updated_at,
                
                // VIDEO FIELDS
                'thumbnail'   => $correct_thumb_url, // Use the fixed URL
                'title'       => $clip->title,
                'description' => strip_tags($clip->description),
                'content'     => $correct_video_url, // Use the fixed URL
                'duration'    => $durationInSeconds,
                'publication_date' => $clip->created_at->toAtomString(),
                'category'    => $clip->category->name,
                'uploader'    => "newsnmf.com"
            ];
        }

        // Make sure you are using the correct "video-sitemap" blade file
        return response()
            ->view('video-sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
    /**
     * HELPER FUNCTION
     * Parses a duration string (e.g., "MM:SS" or "HH:MM:SS") into total seconds.
     *
     * @param string|null $duration
     * @return int|null
     */
    private function parseDurationToSeconds($duration)
    {
        if (empty($duration) || !is_string($duration)) {
            return null;
        }

        $parts = explode(':', $duration);
        $seconds = 0;

        try {
            if (count($parts) === 3) { // HH:MM:SS
                $seconds = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
            } elseif (count($parts) === 2) { // MM:SS
                $seconds = ((int)$parts[0] * 60) + (int)$parts[1];
            } elseif (count($parts) === 1 && is_numeric($parts[0])) { // Already in seconds (SS)
                $seconds = (int)$parts[0];
            } else {
                return null; // Invalid or unhandled format
            }
        } catch (\Exception $e) {
            // In case $parts[n] is not a valid number, etc.
            return null;
        }
        
        return $seconds > 0 ? $seconds : null;
    }




 }
