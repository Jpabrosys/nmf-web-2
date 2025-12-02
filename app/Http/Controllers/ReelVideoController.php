<?php

namespace App\Http\Controllers;
use App\Models\Clip;
use App\Models\Category;
use App\Models\Ads;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FileFacade;

class ReelVideoController extends Controller
{

    public function showVideo($cat_name, $name)
    {
        $currentVideo = Clip::where('site_url', $name)
            ->where('status', 1)
            ->firstOrFail();
         $currentVideo->increment('webViewCount');
        // Optional: validate the category slug (or ignore if unnecessary)
        if ($currentVideo->category && $currentVideo->category->site_url !== $cat_name) {
            // Optional: redirect to correct URL
            return redirect()->route('reelsVideo', [
                'cat_name' => $currentVideo->category->site_url,
                'name' => $currentVideo->site_url
            ]);
        }

        // Get all active videos, optionally filter by category
        $videos = Clip::where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        // Find current index
        $currentIndex = $videos->search(fn ($v) => $v->id == $currentVideo->id);

        return view('reelsVideo', [
            'videos' => $videos,
            'currentIndex' => $currentIndex,
        ]);
    }
    
    public function reelVideosFile(Request $request)
    {    
        $clips = Clip::orderBy('id', 'DESC');
        if (isset($request->title)) {
            $clips->Where('title', 'like', '%' .$request->title . '%');
        }
         // $clips = $clips->paginate(20);

         $perPage = $request->input('perPage', 20);
         $clips = $clips->where('file_type', 'video/mp4')->paginate($perPage);

        if (isset($request->title)) {
            $title = $request->title;
            $clips->setPath(asset('/reel-videos').'?title='.$title);
        } else {
            $title = '';
            $clips->setPath(asset('/reel-videos'));
        }
        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }

        // Set the pagination path with query parameters
        $clips->setPath(asset('/reel-videos') . '?' . http_build_query($queryParams));

        return view('admin/reelVideoFile')->with('data',['clips' => $clips, 'title' => $title,  'perPage' => $perPage ]);
    }
    public function reelVideoAdd(Request $request)
    {
        $categories = Category::where('home_page_status', 1)->get();
        return view('admin/addReelVideo')->with('data',['categories' => $categories]);
    }
    public function addReelVideo(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:mp4|max:20480',  // max 20 MB, no min size'
            'name' => 'required|string|max:255',
            'eng_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category' => 'required|integer|exists:categories,id',
            'thumbnail' => 'required|file|mimes:jpg,jpeg,png|max:100' // max 100KB
        ],[
            'file.max' => 'The reel video file size must not exceed 20 MB.',
            'category.exists' => 'The category field is required.',
            'thumbnail.max' => 'The thumb image size must not exceed 100 KB.',
            'eng_name.required' => 'The English name field is required.',
            //'thumbnail.dimensions' => 'The thumbnail dimensions must be exactly 430x750 pixels.',
        ]);

        // Determine the current year and month
        $year = date('Y');
        $month = date('m');

        $basePath = public_path("file/shortvideos/");

        // Define the reel video path
        $clipsVideoPath = $basePath . "clips". '/' . $year . '/' .$month;

        // Define the reel video thumb image path
        $clipsImagePath = $basePath . "thumbImage". '/'. $year . '/' .$month;

        $fileName = null;
        if ($request->hasFile('file')) {
            $fileName = $request->file->getClientOriginalName();
            $fileName = str_replace(' ', '_',$fileName);
            $fileName = pathinfo($fileName, PATHINFO_FILENAME).time() . '.'. $request->file->extension();
        }

        $thumbFileName = null;
        if ($request->hasFile('thumbnail')) {
            $thumbFileName = $request->thumbnail->getClientOriginalName();
            $thumbFileName = str_replace(' ', '_',$thumbFileName);
            $thumbFileName = pathinfo($thumbFileName, PATHINFO_FILENAME).time() . '.'. $request->thumbnail->extension();
        }

        $siteURL = Str::slug($request->eng_name);

        // Sequence logic → new video always goes first
        // $sortOrder = 1;
        // if ($request->has('add_to_Sequence')) {
            // Push all existing videos down by 1
            Clip::where('SortOrder', '>', 0)->increment('SortOrder');
            $sortOrder = 1;
            Clip::where('SortOrder', '>', 30)->update(['SortOrder' => 0]);
        // }

        Clip::create(
                [
                    "title" => $request->name,
                    "eng_name" => $request->eng_name,
                    "site_url" => $siteURL,
                    "status" => '1',
                    "description" => $request->description,
                    "clip_file_name" => $fileName,
                    "subtitle" => $request->subtitle,
                    "thumb_image" => $thumbFileName,
                    "file_size" => $request->file->getSize(),
                    "video_path" => $clipsVideoPath,
                    "image_path" => $clipsImagePath,
                    "file_type" => $request->file->getClientMimeType(),
                    "likes" => $request->likes ?? 0,  // Default to 0 if not provided
                    "shares" => $request->shares ?? 0, // Default to 0 if not provided
                    'categories_id' => $request->category,
                    "SortOrder" => $sortOrder   // <-- save SortOrder based on checkbox
                ]
        );

        if ($fileName) {
        $request->file->move($clipsVideoPath,$fileName);
        }

        if ($thumbFileName!=null) {
        $request->thumbnail->move($clipsImagePath, $thumbFileName);
        }

        app(\App\Services\ExportHome::class)->run();


        return redirect(config('global.base_url').'reel-videos')->with('success', 'Reel video has been uploaded successfully!');
    }
    public function editReelVideo($id)
    {
        $clips = Clip::get()->where('id', $id)->first();
        $categories = Category::where('home_page_status', 1)->get();
        return view('admin/editReelVideo')->with('data',['clips' => $clips, 'categories' => $categories]);
    }
    public function reelVideoEdit($id, Request $request)
    {
        $request->validate([
            'file' => 'sometimes|file|mimes:mp4|max:20480',  // max 20 MB, no min size'
            'name' => 'required|string|max:255',
            'eng_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category' => 'required|integer|exists:categories,id',
            'thumbnail' => 'sometimes|file|mimes:jpg,jpeg,png|max:100', // max 100KB
            'subtitle' => 'nullable|string|max:255', // ✅ added
        ],[
            'file.max' => 'The reel video file size must not exceed 20 MB.',
            'category.exists' => 'The category field is required.',
            'thumbnail.max' => 'The thumb image size must not exceed 100 KB.',
            'eng_name.required' => 'The English name field is required.',
            //'thumbnail.dimensions' => 'The thumbnail dimensions must be exactly 430x750 pixels.',
        ]);

        // Determine the current year and month
        $year = date('Y');
        $month = date('m');

        $basePath = public_path("file/shortvideos/");

        // Define the reel video path
        $clipsVideoPath = $basePath . "clips". '/' . $year . '/' .$month;

        // Define the reel video thumb image path
        $clipsImagePath = $basePath . "thumbImage". '/'. $year . '/' .$month;

        // Ensure directories exist
        if (!FileFacade::exists($clipsVideoPath)) {
            FileFacade::makeDirectory($clipsVideoPath, 0777, true);
        }
        if (!FileFacade::exists($clipsImagePath)) {
            FileFacade::makeDirectory($clipsImagePath, 0777, true);
        }

        // $clipsVideoPath = public_path('file/shortvideos/clips');
        $clip = Clip::findOrFail($id);

        $fileName = null;
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if (!empty($clip->clip_file_name) && !empty($clip->video_path)) {
                $oldPath = $clip->video_path . '/' . $clip->clip_file_name;
                if (FileFacade::exists($oldPath)) {
                    FileFacade::delete($oldPath);
                }
            }
            
            $fileName = $request->file->getClientOriginalName();
            $fileName = str_replace(' ', '_', $fileName);
            $fileName = pathinfo($fileName, PATHINFO_FILENAME).time() . '.' . $request->file->extension();
        }

        $thumbFileName = null;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if (!empty($clip->thumb_image) && !empty($clip->image_path)) {
                $oldThumbPath = $clip->image_path . '/' . $clip->thumb_image;
                if (FileFacade::exists($oldThumbPath)) {
                    FileFacade::delete($oldThumbPath);
                }
            }

            $thumbFileName = $request->thumbnail->getClientOriginalName();
            $thumbFileName = str_replace(' ', '_',$thumbFileName);
            $thumbFileName = pathinfo($thumbFileName, PATHINFO_FILENAME).time() . '.'. $request->thumbnail->extension();
        }

        $status = 0;
        if($request->status) {
            $status = 1;
        }

        $siteURL = Str::slug($request->eng_name);

        // Handle Sequence Logic
        $sortOrder = $clip->SortOrder;   // default to existing

        if ($clip->SortOrder > 0) {
            if (!$request->has('add_to_Sequence')) {
                // Previously in sequence, but now unchecked → reset
                $sortOrder = 0;
            }
            // else keep as is
        } else {
            if ($request->has('add_to_Sequence')) {
                // Previously not in sequence, now selected → assign new max order
                $maxOrder = Clip::max('SortOrder');
                $sortOrder = $maxOrder + 1;
            } else {
                // Previously not in sequence, still not selected → keep 0
                $sortOrder = 0;
            }
        }

        $updateData = [
            "title" => $request->name,
            "eng_name" => $request->eng_name,
            "site_url" => $siteURL,
            "status" => $status,
            "description" => $request->description,
            "subtitle" => $request->subtitle,
            "likes" => $request->likes ?? 0,  // Default to 0 if not provided
            "shares" => $request->shares ?? 0, // Default to 0 if not provided
            "categories_id" => $request->category,
            "SortOrder" => $sortOrder   // <-- save SortOrder based on checkbox
        ];
        
        if ($fileName) {
            $updateData["clip_file_name"] = $fileName;
            $updateData["file_size"] = $request->file->getSize();
            $updateData["file_type"] = $request->file->getClientMimeType();
            $updateData["video_path"] = $clipsVideoPath;
            $updateData["image_path"] = $clipsImagePath;
        }
        
        if ($thumbFileName) {
            $updateData["thumb_image"] = $thumbFileName;
        }
        
        $clip->update($updateData);

        if ($fileName) {
            $request->file->move($clipsVideoPath,$fileName);
            }
    
        if ($thumbFileName) {
        $request->thumbnail->move($clipsImagePath, $thumbFileName);
        }


        app(\App\Services\ExportHome::class)->run();

        return redirect(config('global.base_url').'reel-videos')->with('success',  "Reel video has been edited successfully!");
    }
    public function uploadReelVideo(Request $request)
    {
        // Determine the current year and month
        $year = date('Y');
        $month = date('m');

        $basePath = public_path("file/shortvideos/");

        // Define the reel video path
        $clipsVideoPath = $basePath . "clips". '/' . $year . '/' .$month;

        // Define the reel video thumb image path
        $clipsImagePath = $basePath . "thumbImage". '/'. $year . '/' .$month;

        // Get the uploaded file
        $file = $request->file('file');

        // Get the file's original extension
        $extension = $file->getClientOriginalExtension();

        // Get the original filename without extension
        $originalFileName = $file->getClientOriginalName();
        $fileNameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);

        // Replace spaces with underscores and add a timestamp for uniqueness
        $fileName = str_replace(' ', '_', $fileNameWithoutExt) . time() . '.' . $extension;
        
        $file_data = Clip::create(
            [
                "status" => '1',
                "clip_file_name" => $fileName,
                "file_type" => $request->file->getClientMimeType(),
                "file_size" => $request->file->getSize(),
                "video_path" => $clipsVideoPath,
                "image_path" => $clipsImagePath
            ]
        );
        //Move the file to the destination path
        $file->move($clipsVideoPath, $fileName);
        app(\App\Services\ExportHome::class)->run();

        return response()->json(['file_id' => $file_data->id, 'clip_file_name' => $fileName, 'box' => $request->box, 'success'=> true]);
    }
    public function del($id, Request $request) 
    {
        ?>
        <script>
            if (confirm('Are you sure? This action will permanently delete this reel video.')) {
                window.location.href =  '<?php echo asset('/reel-videos/del').'/'.$id; ?>'
            } else {
                window.location.href =  '<?php echo asset('/reel-videos'); ?>'
            }
        </script>
        <?php
    }
    public function deleteReelVideo($id, Request $request)
    {
        // Retrieve the Clip record
        $clip = Clip::find($id);

        $clipPath = $clip->video_path;
        if (strpos($clipPath, 'file') !== false) 
        {
            $findfilepos = strpos($clipPath, 'file');                      
            $clipFilePath = substr($clipPath, $findfilepos);
            $clipFilePath = $clipFilePath . '/' . $clip->clip_file_name;
        }

        $clipThumbImagePath = $clip->image_path;
        if (strpos($clipThumbImagePath, 'file') !== false) 
        {
            $findfilepos = strpos($clipThumbImagePath, 'file');                      
            $clipThumbFilePath = substr($clipThumbImagePath, $findfilepos);
            $clipThumbFilePath = $clipThumbFilePath . '/' . $clip->thumb_image;
        }

        // Delete video file if it exists
        if (!empty($clip->clip_file_name) && file_exists($clipFilePath)) {
            unlink($clipFilePath);
        }

        // Delete thumbnail image if it exists
        if (!empty($clip->thumb_image) && file_exists($clipThumbFilePath)) {
            unlink($clipThumbFilePath);
        }

        // // Delete the database record
        $clip->delete();
        app(\App\Services\ExportHome::class)->run();

        return redirect(config('global.base_url').'reel-videos')->with('success', 'Reel video has been deleted successfully.');
    }

    // public function getAllShortVideos()
    // {
    //     $categoriesPerPage = 7;

    //     // Get all categories that have at least one active reel
    //     $categoryIds = Clip::where('status', 1)
    //         ->pluck('categories_id')
    //         ->unique();

    //     $categories = Category::whereIn('id', $categoryIds)
    //         ->paginate($categoriesPerPage);

    //     // If you have ads specific to reel page (optional)
    //     $reelAds = Ads::where('page_type', 'category')->get()->keyBy('location');

    //     // For each category, eager load up to 5 reels
    //     foreach ($categories as $category) {
    //         $category->reels = Clip::where('status', 1)
    //             ->where('categories_id', $category->id)
    //             ->orderBy('id', 'DESC')
    //             ->limit(5)
    //             ->get();
    //     }

    //     $headerTitle = 'शॉर्ट वीडियो'; // Change as needed

    //     return view('short-videos', [
    //         'categories' => $categories,
    //         'headerTitle' => $headerTitle,
    //         'reelAds' => $reelAds
    //     ]);
    // }

    public function getAllShortVideos()
    {
        $videosPerPage = 20; // adjust how many reels you want per page

        // Fetch all active reels, newest first
        $reels = Clip::where('status', 1)
            ->orderBy('id', 'DESC')
            ->paginate($videosPerPage);

        // If you have ads specific to reel page (optional)
        $reelAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        $headerTitle = 'शॉर्ट वीडियो'; // Change as needed

        return view('short-videos', [
            'reels' => $reels,
            'headerTitle' => $headerTitle,
            'reelAds' => $reelAds
        ]);
    }

    public function getShortVideosByCat($name)
    {
        $page = request('page', 1); // Laravel helper
        $count = 20;

        // Get the category
        $category = Category::where('site_url', $name)->first();
        if (!$category) {
            return view('error');
        }

        // Get ads for this page
        $reelCategoryAds = Ads::where('page_type', 'category')->get()->keyBy('location');
        // If you want to reuse same ads as webstories:
        // $reelCategoryAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        // Get reels for this category
        $reels = Clip::where('status', 1)
            ->where('categories_id', $category->id)
            ->orderBy('id', 'DESC')
            ->paginate($count);

        return view('shortVideoByCategory', [
            'reels' => $reels,
            'page' => $page,
            'count' => $count,
            'headerTitle' => $category->name,
            'category' => $category,
            'reelCategoryAds' => $reelCategoryAds
        ]);
    }

    public function shortVideoLoadMore(Request $request)
    {
        try {
            $offset = (int) $request->input('offset', 0);
            $limit = 12; // same as getAllShortVideos()
            
            // Fetch active reels with offset
            $reels = Clip::where('status', 1)
                ->orderBy('id', 'DESC')
                ->skip($offset)
                ->take($limit)
                ->get();

            // Fetch ads if needed for blade
            $reelAds = Ads::where('page_type', 'category')->get()->keyBy('location');

            // Render HTML from partial
            $html = view('components.category.short-video-grid', [
                'reels' => $reels,
                'reelAds' => $reelAds
            ])->render();

            return response()->json([
                'reels' => $html,
                'count' => $reels->count()
            ]);
        } catch (\Exception $e) {
            Log::error('shortVideoLoadMore error: '.$e->getMessage());
            return response()->json(['reels' => '', 'count' => 0], 500);
        }
    }

    public function reelVideoSequence()
    {
        $clips = Clip::orderBy('SortOrder', 'ASC')->where('status', 1)->where('SortOrder', '>', 0)->get();

        return view('admin.shortVideoSequence', compact('clips'));
    }

    public function updateReelVideoSequence(Request $request)
    {
        $items = $request->input('order', []);

        foreach ($items as $item) {
            DB::table('clips')
                ->where('id', $item['id'])
                ->update(['SortOrder' => $item['sequence_id']]);
        }

        app(\App\Services\ExportHome::class)->run();

        return response()->json(['success' => true]);
    }

    public function resetReelVideoSequence($id)
    {
        Clip::where('id', $id)->update([
            'SortOrder'   => 0,
            'updated_at'  => DB::raw('updated_at')
        ]);
        app(\App\Services\ExportHome::class)->run();

        return response()->json(['success' => true]);
    }




}

