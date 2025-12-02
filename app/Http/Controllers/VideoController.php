<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use App\Models\Ads;
use App\Models\Blog;
use App\Models\State;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File as FileFacade;
use getID3;
use Jenssegers\Agent\Agent;

class VideoController extends Controller
{
    public function getAllVideos(Request $request)
    {
        $query = Video::with('category')->orderBy('id', 'DESC');

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $perPage = $request->input('perPage', 20);

        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }

        $videos = $query->paginate($perPage);

        $title = $request->input('title', '');

        $videos->setPath(asset('/video') . '?' . http_build_query($queryParams));

        return view('admin/allVideoList', [
            'videos' => $videos,
            'title' => $title,
            'perPage' => $perPage
        ]);
    }

    public function showVideo($cat_name, $name)
    {
         $agent = new Agent();

        // Mobile → Redirect to AMP
        if ($agent->isMobile()) {
            return redirect()->route('showVideoAmp', [
                'cat_name' => $cat_name,
                'name' => $name
            ]);
        }
        $user = Auth::user();

        $video = Video::with(['category', 'author'])->where('site_url', $name)->first();

        if (!$video || !$video->category || $video->category->site_url !== $cat_name) {
            return abort(404);
        }
        
        // Increase website view count
        Video::where('id', $video->id)->increment('webViewCount');

        $detailsAds = Ads::where('page_type', 'details')->get()->keyBy('location');

        $latests = Video::with('category')
        ->where('is_active', 1)
        ->where('id', '!=', $video->id)
        ->latest()
        ->limit(6)
        ->get();


        // Define widget categories
        $widgetCategoryNames = ['ट्रेंडिंग न्यूज़', 'पॉडकास्ट', 'टेक्नोलॉजी', 'स्पेशल्स'];

        $widgetCategories = Category::whereIn('name', $widgetCategoryNames)->get()->keyBy('name');

        $sideWidgets = [];
        foreach ($widgetCategoryNames as $name) {
            if ($widgetCategories->has($name)) {
                $cat = $widgetCategories[$name];
                $blogs = Blog::with('category') // If needed, you can eager-load category
                    ->where('status', 1)
                    ->where('categories_ids', $cat->id)
                    ->latest('updated_at')
                    ->limit($name === 'पॉडकास्ट' ? 1 : 5)
                    ->get();

                if ($blogs->count()) {
                    $sideWidgets[] = [
                        'categoryName' => $name,
                        'category' => $cat,
                        'blogs' => $blogs,
                    ];
                }
            }
        }

        return view('videoDetail', compact(
            'video',
            'latests',
            'user',
            'detailsAds',
            'sideWidgets'
        ));
    }
    public function showVideoAmp($cat_name, $name)
    {
        // Fetch the video
        $video = Video::with(['category', 'author'])->where('site_url', $name)->first();

        // Validate video + category
        if (!$video || !$video->category || $video->category->site_url !== $cat_name) {
            return abort(404);
        }

        // ---- Extract YouTube Video ID (AMP Safe) ----
        $youtubeVideoId = null;

        if (!empty($video->link)) {
            $pattern = '/(?:youtube\.com\/embed\/|youtu\.be\/|v=)([\w\-]+)/';

            if (preg_match($pattern, $video->link, $matches)) {
                $youtubeVideoId = $matches[1];
            }
        }

        // Load ads for details page
        $detailsAds = Ads::where('page_type', 'details')->get()->keyBy('location');

        // Latest videos (for sidebar)
        $latests = Video::with('category')
        ->where('is_active', 1)
        ->where('id', '!=', $video->id)
        ->latest()
        ->limit(6)
        ->get();


        // SIDE WIDGETS (same logic as normal)
        $widgetCategoryNames = ['ट्रेंडिंग न्यूज़', 'पॉडकास्ट', 'टेक्नोलॉजी', 'स्पेशल्स'];

        $widgetCategories = Category::whereIn('name', $widgetCategoryNames)->get()->keyBy('name');

        $sideWidgets = [];
        foreach ($widgetCategoryNames as $name) {
            if ($widgetCategories->has($name)) {
                $cat = $widgetCategories[$name];

                $blogs = Blog::with('category')
                    ->where('status', 1)
                    ->where('categories_ids', $cat->id)
                    ->latest('updated_at')
                    ->limit($name === 'पॉडकास्ट' ? 1 : 5)
                    ->get();

                if ($blogs->count()) {
                    $sideWidgets[] = [
                        'categoryName' => $name,
                        'category' => $cat,
                        'blogs' => $blogs,
                    ];
                }
            }
        }

        // Device detection
        $agent = new Agent();

        // Return AMP view
        return view('videoDetail-amp', [
            'video' => $video,
            'latests' => $latests,
            'detailsAds' => $detailsAds,
            'sideWidgets' => $sideWidgets,
            'youtubeVideoId' => $youtubeVideoId,
            'isMobile' => $agent->isMobile()
        ]);
    }


    public function addVideo(Request $request)
    {
        $categories = Category::where('home_page_status', 1)->get();
        $states = State::where('home_page_status', 1)->get();
        $authors = User::where('status', 1)->get();
        return view('admin/addVideoArticle', ['categories' => $categories, 'authors' => $authors, 'states' => $states]);
    }

    public function saveVideo(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'title_url' => 'required|string|max:255|unique:videos,site_url',
            'category' => 'required|exists:categories,id',
            'author' => 'required|exists:users,id',
            'description' => 'required|string',
            'state' => 'nullable|integer',
            'keywords' => 'nullable|string|max:255',
            'image_file' => 'required|file|mimes:jpeg,jpg,png,webp|max:200', // 200 KB
            'video_file' => 'required|file|mimes:mp4|max:307200', // 300 MB
        ];

        $messages = [
            'title.required' => 'Title is required.',
            'title_url.required' => 'Title URL is required.',
            'title_url.unique' => 'This Title URL already exists.',
            'category.required' => 'Please select a category.',
            'category.exists' => 'Selected category is invalid.',
            'author.required' => 'Please select an author.',
            'author.exists' => 'Selected author is invalid.',
            'description.required' => 'Description is required.',

            'image_file.required' => 'Thumbnail image is required.',
            'image_file.mimes' => 'Only JPEG, JPG, PNG, or WEBP formats are allowed.',
            'image_file.max' => 'Image must not exceed 200 KB.',

            'video_file.required' => 'Video file is required.',
            'video_file.mimes' => 'Only MP4 format is allowed for video.',
            'video_file.max' => 'Video size must not exceed 300 MB.',
        ];

        $request->validate($rules, $messages);

        $year = date('Y');
        $month = date('m');

        $thumbPath = public_path("file/video/$year/$month/thumbnails");
        $videoPath = public_path("file/video/$year/$month");

        FileFacade::makeDirectory($thumbPath, 0755, true, true);
        FileFacade::makeDirectory($videoPath, 0755, true, true);

        $image = $request->file('image_file');
        $video = $request->file('video_file');

        $image = $request->file('image_file');
        $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanImageName = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $originalImageName));
        $thumbnailFilename = $cleanImageName . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move($thumbPath, $thumbnailFilename);

        $video = $request->file('video_file');
        $originalVideoName = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanVideoName = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $originalVideoName));
        $videoFilename = $cleanVideoName . '_' . time() . '.' . $video->getClientOriginalExtension();
        $videoSize = $video->getSize();
        $video->move($videoPath, $videoFilename);

        $siteURL = Str::slug($request->title_url);

        // Extract duration and format using getID3
        require_once('getid3/getid3.php');
        $getID3 = new \getID3;
        $analyzed = $getID3->analyze($videoPath . DIRECTORY_SEPARATOR . $videoFilename);

        $duration = null;
        if (isset($analyzed['playtime_seconds'])) {
            $seconds = (int)$analyzed['playtime_seconds'];
            $duration = $seconds >= 3600
                ? gmdate('H:i:s', $seconds)
                : gmdate('i:s', $seconds);
        }

        $format = $analyzed['fileformat'] ?? 'mp4';

        $finalVideoPath = "file/video/$year/$month/$videoFilename";
        $finalThumbnailPath = "file/video/$year/$month/thumbnails/$thumbnailFilename";

        Video::create([
            'title' => $request->title,
            'eng_name' => $request->title_url,
            'site_url' => $siteURL,
            'author_id' => $request->author,
            'category_id' => $request->category,
            'description' => $request->description,
            'video_path' => $finalVideoPath,
            'thumbnail_path' => $finalThumbnailPath,
            'state_id' => ($request->filled('state') && $request->state != 0) 
              ? $request->state 
              : null,
            'keywords' => $request->filled('keywords') ? $request->keywords : null,
            'file_size' => $videoSize,
            'duration' => $duration,
            'format' => $format,
            'is_active' => 1,
            'views' => 0,
            'published_at' => now(),
        ]);

        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        //return redirect('/video')->with('success', 'Video uploaded successfully!');
        return response()->json([
            'status' => 'success',
            'redirect_url' => config('global.base_url').('video'),
            'message' => 'Video uploaded successfully!',
        ]);
    }

    public function editVideo($id)
    {
        $video = Video::find($id);
        $categories = Category::where('home_page_status', 1)->get();
        $states = State::where('home_page_status', 1)->get();
        $authors = User::where('status', 1)->get();

        if (!$video) {
            dump("No file found with ID: " . $id);
        }
        return view('admin/editVideoArticle', compact('video', 'categories', 'states', 'authors'));
    }

    public function updateVideo(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        // Check if image or video exists
        $hasImage = !empty($video->thumbnail_path) && FileFacade::exists(public_path($video->thumbnail_path));
        $hasVideo = !empty($video->video_path) && FileFacade::exists(public_path($video->video_path));

        $rules = [
            'title' => 'required|string|max:255',
            'title_url' => 'required|string|max:255|unique:videos,site_url,' . $video->id,
            'category' => 'required|exists:categories,id',
            'state' => 'nullable|integer',
            'keywords' => 'nullable|string|max:255',
            'author' => 'required|exists:users,id',
            'description' => 'required|string',
        ];

        // Conditionally apply validation rules for image
        if (!$hasImage && !$request->hasFile('image_file')) {
            $rules['image_file'] = 'required|file|mimes:jpeg,jpg,png,webp|max:200';
        } elseif ($request->hasFile('image_file')) {
            $rules['image_file'] = 'file|mimes:jpeg,jpg,png,webp|max:200';
        }

        // Conditionally apply validation rules for video
        if (!$hasVideo && !$request->hasFile('video_file')) {
            $rules['video_file'] = 'required|file|mimes:mp4|max:307200'; // 300 MB
        } elseif ($request->hasFile('video_file')) {
            $rules['video_file'] = 'file|mimes:mp4|max:307200';
        }

        $messages = [
            'title.required' => 'Title is required.',
            'title_url.required' => 'Title URL is required.',
            'title_url.unique' => 'This Title URL already exists.',
            'category.required' => 'Please select a category.',
            'category.exists' => 'Selected category is invalid.',
            'author.required' => 'Please select an author.',
            'author.exists' => 'Selected author is invalid.',
            'description.required' => 'Description is required.',

            'image_file.required' => 'Thumbnail image is required.',
            'image_file.mimes' => 'Only JPEG, JPG, PNG, or WEBP formats are allowed.',
            'image_file.max' => 'Image must not exceed 200 KB.',

            'video_file.required' => 'Video file is required.',
            'video_file.mimes' => 'Only MP4 format is allowed for video.',
            'video_file.max' => 'Video size must not exceed 300 MB.',
        ];

        $request->validate($rules, $messages);

        // ==== File handling ====

        $year = date('Y');
        $month = date('m');
        $thumbPath = public_path("file/video/$year/$month/thumbnails");
        $videoPath = public_path("file/video/$year/$month");

        FileFacade::makeDirectory($thumbPath, 0755, true, true);
        FileFacade::makeDirectory($videoPath, 0755, true, true);

        $finalThumbnailPath = $video->thumbnail_path;
        if ($request->hasFile('image_file')) {

            if (!empty($video->thumbnail_path)) {
                $oldImagePath = public_path($video->thumbnail_path);
                if (FileFacade::exists($oldImagePath)) {
                    FileFacade::delete($oldImagePath);
                }
            }

            $image = $request->file('image_file');
            $cleanImageName = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)));
            $thumbnailFilename = $cleanImageName . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move($thumbPath, $thumbnailFilename);
            $finalThumbnailPath = "file/video/$year/$month/thumbnails/$thumbnailFilename";
        }

        $finalVideoPath = $video->video_path;
        $videoSize = $video->file_size;
        $duration = $video->duration;
        $format = $video->format;

        if ($request->hasFile('video_file')) {

            if (!empty($video->video_path)) {
                $oldVideoPath = public_path($video->video_path);
                if (FileFacade::exists($oldVideoPath)) {
                    FileFacade::delete($oldVideoPath);
                }
            }

            $videoFile = $request->file('video_file');
            $cleanVideoName = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME)));
            $videoFilename = $cleanVideoName . '_' . time() . '.' . $videoFile->getClientOriginalExtension();
            $videoSize = $videoFile->getSize();
            $videoFile->move($videoPath, $videoFilename);
            $finalVideoPath = "file/video/$year/$month/$videoFilename";

            require_once('getid3/getid3.php');
            $getID3 = new \getID3;
            $analyzed = $getID3->analyze($videoPath . DIRECTORY_SEPARATOR . $videoFilename);

            if (isset($analyzed['playtime_seconds'])) {
                $seconds = (int)$analyzed['playtime_seconds'];
                $duration = $seconds >= 3600 ? gmdate('H:i:s', $seconds) : gmdate('i:s', $seconds);
            }

            $format = $analyzed['fileformat'] ?? 'mp4';
        }

        $video->update([
            'title' => $request->title,
            'eng_name' => $request->title_url,
            'site_url' => Str::slug($request->title_url),
            'author_id' => $request->author,
            'category_id' => $request->category,
            'description' => $request->description,
            'video_path' => $finalVideoPath,
            'thumbnail_path' => $finalThumbnailPath,
            'state_id' => ($request->filled('state') && $request->state != 0) 
              ? $request->state 
              : null,
            'keywords' => $request->filled('keywords') ? $request->keywords : null,
            'file_size' => $videoSize,
            'duration' => $duration,
            'format' => $format,
            'published_at' => now(),
        ]);
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        //return redirect('/video')->with('success', 'Video updated successfully!');
        return response()->json([
            'status' => 'success',
            'redirect_url' => config('global.base_url').('video'),
            'message' => 'Video uploaded successfully!',
        ]);
    }
    //NL1001:18Sep:2025:Added
        public function destroy($id)
    {
        $video = Video::findOrFail($id);

        // Delete thumbnail if exists
        if (!empty($video->thumbnail_path)) {
            $thumbnailPath = public_path($video->thumbnail_path);
            if (FileFacade::exists($thumbnailPath)) {
                FileFacade::delete($thumbnailPath);
            }
        }

        // Delete video file if exists
        if (!empty($video->video_path)) {
            $videoPath = public_path($video->video_path);
            if (FileFacade::exists($videoPath)) {
                FileFacade::delete($videoPath);
            }
        }

        $video->delete();
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        //return redirect()->back()->with('success', 'Video deleted successfully.');
        return redirect()->to(config('global.base_url').('video'))
    ->with('success', 'Video deleted successfully.');

    

    }
}
