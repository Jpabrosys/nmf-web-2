<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Blog;

class RSSFeedController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status', 1)
            // We eager load 'images' because your blogs table uses 'image_ids' to link to the files table
            ->with(['category', 'authorUser', 'images']) 
            ->whereHas('category')
            ->latest()
            ->take(20)
            ->get();

        $rss = view('feed', compact('blogs'));

        return Response::make($rss, 200, [
            'Content-Type' => 'application/rss+xml; charset=UTF-8'
        ]);
    }
}