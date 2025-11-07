<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use App\Models\Blog;
use App\Models\WebStories;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

 }
