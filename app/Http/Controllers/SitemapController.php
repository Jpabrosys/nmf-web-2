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

        $urls[] = [
            'loc' => url('/videos/' . $video->category->site_url . '/' . $video->site_url),
            'lastmod' => $video->updated_at,

            // VIDEO FIELDS
            'thumbnail' => url($video->thumbnail_path),
            'title' => $video->title,
            'description' => strip_tags($video->description),
            'content' => url($video->video_path),
            'duration' => $video->duration ?? null,
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

    foreach ($clips as $clip) {

        if (!$clip->category) continue;

        $urls[] = [
            'loc'        => url('/reels/' . $clip->category->site_url . '/' . $clip->site_url),
            'lastmod'    => $clip->updated_at,
            'priority'   => '0.5',
            'thumbnail'  => url($clip->thumb_image),
            'title'       => $clip->title,
            'description' => strip_tags($clip->description),
            'category'    => $clip->category->name,
            'published_at'=> $clip->created_at->toAtomString(),
            'uploader'    => "newsnmf.com"
        ];
    }

    return response()
        ->view('reel-sitemap', compact('urls'))
        ->header('Content-Type', 'application/xml');
}




 }
