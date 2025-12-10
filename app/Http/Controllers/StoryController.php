<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Models\State;
use App\Models\SubCategory;
use App\Models\WebStories;
use App\Models\WebStoryFiles;
use App\Models\LiveBlog;
use Illuminate\Support\Facades\Auth;
use App\Models\Ads;
use Carbon\Carbon;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Party;
use App\Models\Mahamukabla;
use App\Models\Candidate;
use App\Models\ElectionResult;
use Jenssegers\Agent\Agent; //  Import Agent
class StoryController extends Controller
{

private function getBlogData($cat_name, $name)
{
    $cacheKey = "blog_details_{$cat_name}_{$name}";

    return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($cat_name, $name) {

        $user = Auth::check() ? Auth::user() : null;

        $blog = Blog::with(['images', 'category', 'authorUser'])->where('site_url', $name)->first();

        if (!$blog || $blog->status != 1) return null;

        $categoryIds = [];
        if (!empty($blog->categories_ids)) $categoryIds[] = (int) $blog->categories_ids;
        if (!empty($blog->mult_cat)) {
            $multiCatIds = array_map('intval', explode(',', $blog->mult_cat));
            $categoryIds = array_merge($categoryIds, $multiCatIds);
        }
        $categoryIds = array_unique($categoryIds);

        $category = Category::where('site_url', $cat_name)->first();
        if (!$category || !in_array($category->id, $categoryIds)) return null;

        $detailsAds = Ads::where('page_type', 'details')->get()->keyBy('location');

        $other_blog = Blog::where('id', '!=', $blog->id)
            ->where('categories_ids', $blog->categories_ids)
            ->with(['thumbnail', 'images'])
            ->limit(6)->get();
         
      
        
        
        $latests = Blog::where('status', 1)
            ->where('categories_ids', $blog->category->id)
            ->where('id', '!=', $blog->id)
            ->whereNull('link')
            ->with(['thumbnail', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(6)->get();

        return [
            'blog' => $blog,
            'relates' => $other_blog,
            'latests' => $latests,
            'category' => $blog->category,
            'author' => $blog->authorUser,
            'user' => $user,
            'detailsAds' => $detailsAds,
        ];
    });
}

private function getYouTubeVideoId($url)
{
    if (empty($url)) {
        return null;
    }
    
    // Regex to match all common YouTube URL formats
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|u\/\w\/|watch\?.+&v=)?([\w\-]{11})(?:.+)?/';
    
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1]; // Returns the 11-character ID
    }
    
    return null; // No match found
}

public function showStory($cat_name, $name)
{
    $agent = new Agent();

    // 🟣 If it's a mobile device, redirect to AMP page
    if ($agent->isMobile()) {
        return redirect()->route('showStoryAmp', [
            'cat_name' => $cat_name,
            'name' => $name
        ]);
    }
    
    $data = $this->getBlogData($cat_name, $name);
    if (!$data) return view('error');

Blog::$skipUpdatedEvent = true;

DB::table('blogs')
    ->where('id', $data['blog']->id)
    ->update([
        'WebHitCount' => DB::raw('WebHitCount + 1')
    ]);

Blog::$skipUpdatedEvent = false;



    $data['blog']->WebHitCount += 1;

    // --- THIS IS THE NEW LINE ---
    $data['youtubeVideoId'] = $this->getYouTubeVideoId($data['blog']->link ?? null);
    // --- END NEW LINE ---

    $comments = $data['blog']->comments()
        ->withCount('likes')
        ->with('replies.viewer')
        ->paginate(10);

    return view('detail')->with('data', [
        ...$data, // This now correctly includes 'youtubeVideoId'
        'comments' => $comments,
        'isLoggedIn' => Auth::guard('viewer')->check(),
        'currentViewer' => Auth::guard('viewer')->user(),
    ]);
}



public function showStoryAmp($cat_name, $name)
{
    // This is the line you marked "Re-type this line"
    $data = $this->getBlogData($cat_name, $name); 
    if (!$data) return view('error');

    Blog::$skipUpdatedEvent = true;

    DB::table('blogs')
        ->where('id', $data['blog']->id)
        ->update([
            'WebHitCount' => DB::raw('WebHitCount + 1')
        ]);

    Blog::$skipUpdatedEvent = false;


    $data['blog']->WebHitCount += 1;
    $data['youtubeVideoId'] = $this->getYouTubeVideoId($data['blog']->link ?? null);

    $comments = $data['blog']->comments()
        ->withCount('likes')
        ->with('replies.viewer')
        ->paginate(10);

    $agent = new Agent();

    return view('detail-amp')->with('data', [
        ...$data, // This now includes the *correct* 'youtubeVideoId'
        'comments' => $comments,
        'isLoggedIn' => Auth::guard('viewer')->check(),
        'currentViewer' => Auth::guard('viewer')->user(),
        'isMobile' => $agent->isMobile(),
    ]);
}



    public function category($name, Request $request)
    {
        $state  = $request->input('state', '');
        $subcat = $request->input('subcat', '');
        $page   = $request->input('page', 1);
        $results = Party::all();
        $count = 13;

        $stateid = 0;
        $stateName = '';
        $stateUrl = '';
        if ($state != "") {
            $stateObj = State::where('site_url', $state)->first();
            if (isset($stateObj)) {
                $stateid = $stateObj->id ?? 0;
                $stateName = $stateObj->name == 'नई दिल्ली' ? 'दिल्ली' : ($stateObj->name ?? '');
                $stateUrl  = $stateObj->site_url ?? '';
            }
        }

        $subcatid = 0;
        $subcatName = '';
        $subcatUrl = '';
        if ($subcat != "") {
            $subcatObj = SubCategory::where('site_url', $subcat)->first();
            if (isset($subcatObj)) {
                $subcatid = $subcatObj->id ?? 0;
                $subcatName = $subcatObj->name ?? '';
                $subcatUrl  = $subcatObj->site_url ?? '';
            }
        }

        $category = Category::where('site_url', $name)->first();
        if (!$category) {
            return view('error');
        }

        if($category->name  == 'विधानसभा चुनाव') {
            // विधायन सभा चुनाव category (special handling)
            $bidhansabhaCategory = Category::where('name', 'विधानसभा चुनाव')->first();
            $bidhansabha_cat_url = $bidhansabhaCategory->site_url ?? '';
        } else {
            $bidhansabha_cat_url = '';
        }

        $categoryAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        // NL1026:16Sep2025: Cache blog category pages for 1 hour :Start
        // cache key
        $cacheKey = "category_page_ids_{$name}_state_{$stateid}_subcat_{$subcatid}";
        $cacheTTL = 60 * 60; // 1 hour

        //  Cache only IDs (top + remaining)
        $cachedData = Cache::remember($cacheKey, $cacheTTL, function () use ($category, $stateid, $subcatid, $count) {

            // Top blogs IDs
            $topBlogsQuery = Blog::where('status', '1')
                ->where(function ($query) use ($category) {
                    $query->where('categories_ids', $category->id)
                        ->orWhereRaw("FIND_IN_SET(?, mult_cat)", [$category->id]);
                })
                ->orderBy('created_at', 'DESC')
                ->limit(5);

            if ($stateid > 0) $topBlogsQuery->where('state_ids', $stateid);
            if ($subcatid > 0) $topBlogsQuery->where('sub_category_id', $subcatid);

            $topBlogIds = $topBlogsQuery->pluck('id')->toArray();

            // Remaining blogs IDs
            $blogsQuery = Blog::where('status', '1')
                ->whereNotIn('id', $topBlogIds)
                ->where(function ($query) use ($category) {
                    $query->where('categories_ids', $category->id)
                        ->orWhereRaw("FIND_IN_SET(?, mult_cat)", [$category->id]);
                })
                ->orderBy('created_at', 'DESC');

            if ($stateid > 0) $blogsQuery->where('state_ids', $stateid);
            if ($subcatid > 0) $blogsQuery->where('sub_category_id', $subcatid);

            $blogIds = $blogsQuery->pluck('id')->toArray();

            //  WebStories IDs
            $webStoryIds = WebStories::where('status', '1')
                ->where('categories_id', $category->id)
                ->orderBy('id', 'DESC')
                ->limit(10)
                ->pluck('id')
                ->toArray();

            return [
                'topBlogIds' => $topBlogIds,
                'blogIds'    => $blogIds,
                'webStoryIds' => $webStoryIds,
            ];
        });

        //  Now re-fetch data with images (fresh each time)
        $topBlogs = Blog::with(['images', 'category', 'state'])
            ->whereIn('id', $cachedData['topBlogIds'])
            ->orderBy('created_at', 'DESC')
            ->get();

        $blogsQuery = Blog::with(['images', 'category', 'state'])
            ->whereIn('id', $cachedData['blogIds'])
            ->orderBy('created_at', 'DESC');

        $blogs = $blogsQuery->paginate($count, ['*'], 'page', $page);
        $blogs->setPath(asset('/') . $name);

        if ($stateid > 0) $blogs->appends(['state' => $state]);
        if ($subcatid > 0) $blogs->appends(['subcat' => $subcat]);

        // Fetch web stories
        $webStories = collect();
        if (!empty($cachedData['webStoryIds'] ?? [])) {
            $webStories = WebStories::whereIn('id', $cachedData['webStoryIds'])
                ->orderBy('id', 'DESC')
                ->get();
        }

        // Sidebar (Global cached sidebar for all category pages)
        $sidebarCategories = [
            ['name' => 'क्या कहता है कानून?', 'limit' => 3],
            ['name' => 'पॉडकास्ट', 'limit' => 1],
            ['name' => 'टेक्नोलॉजी', 'limit' => 5],
            ['name' => 'स्पेशल्स', 'limit' => 5],
        ];

        // Cache key for the whole sidebar
        $cacheKey = 'sidebar_widgets';

        $categoriesData = Cache::remember('sidebar_widgets', now()->addMinutes(60), function () use ($sidebarCategories) {
            $data = [];

            foreach ($sidebarCategories as $cat) {
                $category = Category::where('name', $cat['name'])->first();
                $blogs = Blog::where('status', 1)
                    ->where('categories_ids', $category->id)
                    ->orderBy('updated_at', 'DESC')
                    ->limit($cat['limit'])
                    ->get();

                $data[$cat['name']] = [
                    'categoryName' => $cat['name'],
                    'category'     => $category,
                    'blogs'        => $blogs,
                ];
            }

            return $data;
        });

        // NL1026:16Sep2025: Cache blog category pages for 1 hour :End
        // Final View
        if ($category->site_url === 'sports') {
            return view('categorySports', compact(
                'category', 'topBlogs', 'blogs', 'page', 'count',
                'categoryAds', 'webStories', 'categoriesData',
                'stateName', 'stateUrl', 'subcatName', 'subcatUrl', 'bidhansabha_cat_url'
            ));
        }
        if ($category->site_url === 'dharma-gyan') {
            return view('categoryDharmgyan', compact(
                'category', 'topBlogs', 'blogs', 'page', 'count',
                'categoryAds', 'webStories', 'categoriesData',
                'stateName', 'stateUrl', 'subcatName', 'subcatUrl', 'bidhansabha_cat_url'
            ));
        }

       /*  return view('category', compact(
            'category', 'topBlogs', 'blogs', 'page', 'count',
            'categoryAds', 'webStories', 'categoriesData',
            'stateName', 'stateUrl', 'subcatName', 'subcatUrl', 'bidhansabha_cat_url'
        )); */

   // ---- Fetch Vote Count & Top Party (from ElectionResultController logic) ----
    $voteCounts = ElectionResult::where('show_in_list', -1)->get();
    $topParties = ElectionResult::where('show_in_highlight', -1)->get();

    // ---- Fetch Mahamukabla Data ----
    $candidates = Candidate::select('id','candidate_name','candidate_image','area','c_status','party_id')->get();
    $parties    = Party::select('id','party_name','party_logo')->get();
    $mahamukablas = Mahamukabla::with(['candidate','party'])->orderBy('sequence','ASC')->get();

    // ---- Return View ----
    return view('category', compact(
        'category', 'topBlogs', 'blogs', 'page', 'count',
        'categoryAds', 'results', 'mahamukablas', 'candidates',
        'parties', 'voteCounts', 'topParties', 'webStories',
        'categoriesData', 'stateName', 'stateUrl', 'subcatName',
        'subcatUrl', 'bidhansabha_cat_url'
    ));



    }


    public function privacy()
    {
        return view('privacy');
    }
    public function disclaimer()
    {
        return view('disclaimer');
    }
    public function contact()
    {
        return view('contact');
    }
    // Added breaking news functionality with url date wise
    public function breakingNews($slug)
    {
        $count = 10;
        $page = request()->get('page', 1);
        $search = request()->get('search', '');

        // Get latest breaking blog
        $latestBreakingBlog = Blog::where('breaking_status', 1)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$latestBreakingBlog) {
            abort(404, 'No breaking news found.');
        }

        $breakingAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        $date = Carbon::parse($latestBreakingBlog->created_at);
        $breakingCat = $latestBreakingBlog->categories_ids;



        // Fetch data for the breaking page
        $breakingBlogs = Blog::where('status', 1)
            ->where('breaking_status', 1)
            ->where('sequence_id', 0)
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'DESC')
            ->get();

        $category = Category::where('id', $breakingCat)->first();

        $blog = Blog::where('status', '1')->where('categories_ids', $breakingCat)->orWhereRaw("FIND_IN_SET('.$breakingCat.', 'mult_cat')")->with('images')->orderBy('created_at', 'DESC')->paginate($count);

        return view('breaking', [
            'blogs' => $blog,
            'breakingBlogs' => $breakingBlogs,
            'page' => $page,
            'count' => $count,
            'breakingCat' => $breakingCat,
            'category' => $category,
            'slug' => $slug,
            'breakingAds' => $breakingAds,
        ]);
    }
    public function about()
    {
        return view('about');
    }
    public function videos()
    {
        // Paginate categories directly
        $paginatedCategories = Category::where('home_page_status', '1')->paginate(4);

        $videoAds = Ads::where('page_type', 'category')->get()->keyBy('location');
        // Load blogs for each paginated category
        $categoryWiseBlogs = [];

        foreach ($paginatedCategories as $category) {
            $blogs = Blog::where('status', '1')
                ->whereNotNull('link')
                ->where('status', '1')
                ->where('categories_ids', $category->id)
                ->where('sequence_id', '0')
                ->where('isLive', '0')
                ->orderByDesc('id')
                ->take(6)
                ->get();

            if ($blogs->isNotEmpty()) {
                $categoryWiseBlogs[] = [
                    'category' => $category,
                    'blogs' => $blogs,
                ];
            }
        }

        // Get "All" video blogs (for swiper and top section)
        $allVideoBlogs = Blog::where('status', '1')
            ->whereNotNull('link')
            ->where('sequence_id', '0')
            ->where('isLive', '0')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('allVideos', compact('allVideoBlogs', 'categoryWiseBlogs', 'paginatedCategories', 'videoAds'));
    }
    public function videosCategory($cat_name)
    {
        $category = Category::where('site_url', $cat_name)->first();

        if (!$category) {
            return view('error');
        }

        $videoCatAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        // Fetch all blogs for this category with pagination
        $blogs = Blog::where('status', '1')
            ->whereNotNull('link')
            ->where('status', '1')
            ->where('categories_ids', $category->id)
            ->where('sequence_id', '0')
            ->where('isLive', '0')
            ->orderByDesc('id')
            ->paginate(18);

        return view('allVideosCategory', compact('blogs', 'category', 'videoCatAds'));
    }
    public function photos()
    {
        return view('allPhotos');
    }

    public function search()
    {
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
        $count = 18;
        //$category = Category::where('site_url', $name)->first();
        $searchAd = Ads::where('page_type', 'category')->get()->keyBy('location');
        $blog = Blog::where('status', '1')->where('breaking_status', 0)->where('name', 'like', '%' . $search . '%')->orWhere('tags', 'like', '%' . $search . '%')->where('status', 1)->with('images')->orderBy('created_at', 'DESC')->paginate($count);
        $blog->setPath(asset('/search') . '?search=' . $search);
        return view('search', ['blogs' => $blog, 'search' => $search, 'page' => $page, 'count' => $count, 'searchAd' => $searchAd]);
    }
    public function author($name)
    {
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $count = 15;
        $name = str_replace('_', ' ', $name);
        $user  = User::where('url_name', $name)->first();

        $authorAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        if (!isset($user->id)) {
            $blog = Blog::whereNull('author')->where('status', 1)->with('images')->orderBy('created_at', 'DESC')->paginate($count);
        } else {
            $blog = Blog::where('author', isset($user->id) ? $user->id : 'NULL')->where('status', 1)->with('images')->orderBy('created_at', 'DESC')->paginate($count);
        }
        $blog->setPath(asset('/author') . "/" . $name);
        return view('author', ['users' => $user, 'blogs' => $blog, 'page' => $page, 'count' => $count, 'authorAds' => $authorAds]);
    }
    public function state($name)
    {
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $count = 13;

        $name = str_replace('_', ' ', $name);
        $state  = State::where('site_url', $name)->first();

        if (!$state) return view('error');

        $sateAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        // Step 1: Get fixed top 5 latest blogs
        $topBlogs = Blog::where('state_ids', $state->id)
            ->where('status', 1)
            ->with('images')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        $topBlogIds = $topBlogs->pluck('id')->toArray();

        // Step 2: Get paginated blogs excluding top 5
        $blogs = Blog::where('state_ids', $state->id)
            ->where('status', 1)
            ->whereNotIn('id', $topBlogIds)
            ->with('images')
            ->orderBy('created_at', 'DESC')
            ->paginate($count);

        $blogs->setPath(asset('/state') . "/" . $name);

        return view('state', [
            'state' => $state,
            'topBlogs' => $topBlogs,
            'blogs' => $blogs,
            'page' => $page,
            'count' => $count,
            'sateAds' => $sateAds,
        ]);
    }


    //Get All Web Stories 
    public function getAllwebStories()
    {
        $categoriesPerPage = 7;

        // Get all categories that have at least one active web story
        $categoryIds = WebStories::where('status', '1')->pluck('categories_id')->unique();
        $categories = Category::whereIn('id', $categoryIds)->paginate($categoriesPerPage);

        $webStoryAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        // For each category, eager load up to 5 web stories
        foreach ($categories as $category) {
            $category->webStories = WebStories::where('status', '1')
                ->where('categories_id', $category->id)
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->get();
        }

        $headerTitle = 'वेब स्टोरीज़';

        return view('webstories', [
            'categories' => $categories,
            'headerTitle' => $headerTitle,
            'webStoryAds' => $webStoryAds
        ]);
    }

    //Get All Web Stories By Category
    public function webStoriesByCategory($name)
    {
        $page = request('page', 1); // Laravel helper
        $count = 20;

        $category = Category::where('site_url', $name)->first();

        if (!$category) {
            return view('error');
        }

        $webStoryCategoryAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        $webStories = WebStories::where('status', 1)
            ->where('categories_id', $category->id)
            ->orderBy('id', 'DESC')
            ->paginate($count);

        return view('webstoriesWithCategory', [
            'webStories' => $webStories,
            'page' => $page,
            'count' => $count,
            'headerTitle' => $category->name,
            'category' => $category,
            'webStoryCategoryAds' => $webStoryCategoryAds
        ]);
    }

    //Get Web Stories Details
    public function webStoryDetail($cat_name, $name)
    {
        $story = WebStories::where('siteurl', $name)->first();
        if (!$story) {
            return view('error');
        }

        WebStories::where('id', $story->id)->increment('webViewCount');
        
        $category = Category::where('site_url', $cat_name)->first();
        if (!$category) {
            return view('error');
        }

        $webstories = WebStoryFiles::where('webstories_id', $story->id)
            ->where('display_status', 1)
            ->orderBy('file_sequence', 'asc')
            ->limit(15)
            ->get()
            ->all();

        // First: Get stories after current one
        $nextStories = WebStories::where('status', 1)
            ->where('id', '>', $story->id)
            ->where('categories_id', $category->id)
            ->orderBy('id')
            ->take(4)
            ->get();

        $countNeeded = 4 - $nextStories->count();

        // If not enough, fill from start (excluding current story)
        if ($countNeeded > 0) {
            $additionalStories = WebStories::where('status', 1)
                ->where('id', '!=', $story->id)
                ->where('categories_id', $category->id)
                ->orderBy('id')
                ->take($countNeeded)
                ->get();

            // Merge both
            $nextStories = $nextStories->merge($additionalStories);
        }

        // For each next story, get its first image
        $nextStoriesWithImages = $nextStories->map(function ($nextStory) {
            $image = WebStoryFiles::where('webstories_id', $nextStory->id)
                ->where('display_status', 1)
                ->orderBy('file_sequence', 'asc')
                ->first();

            return [
                'story' => $nextStory,
                'image' => $image,
            ];
        });

        return view('webStoryDetail', [
            'story' => $story,
            'webstories' => $webstories,
            'category' => $category,
            'nextStoriesWithImages' => $nextStoriesWithImages
        ]);
    }
    //Increase WebHitCount
    // public function increaseWebHitCount(Request $request)
    // {
    //     $blogId = $request->input('blog_id');
    //     if ($blogId) {
    //         $originalUpdatedAt = Blog::where('id', $blogId)->value('updated_at');
    //         Blog::where('id', $blogId)->update([
    //             'WebHitCount' => DB::raw('WebHitCount + 1'),
    //             'updated_at' => $originalUpdatedAt,
    //         ]);
    //         return response()->json(['status' => 'success']);
    //     }
    // }

    public function liveBlogs($cat_name, $name)
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            // Redirect mobile users to the new AMP route
            return redirect()->route('liveBlogsAmp', [
                'cat_name' => $cat_name,
                'name' => $name
            ]);
        }
        $category = Category::where('site_url', $cat_name)->first();

        if (!$category) {
            return view('error');
        }

        $blog = Blog::where('site_url', $name)->with('images')->first();

        if (!$blog) {
            return view('error');
        }
        $author =  User::where('id', $blog->author)->first();

        $liveDetailAds = Ads::where('page_type', 'details')->get()->keyBy('location');

        // Efficiently stream the live blogs using cursor to minimize memory usage
        $liveBlogs = collect(
            LiveBlog::where('status', '1')
                ->where('blog_id', $blog->id)
                ->orderBy('created_at', 'DESC')
                ->cursor()
        );

        return view('liveBlogs', [
            'blogs' => $blog,
            'category' => $category,
            'liveBlogs' => $liveBlogs,
            'author' => $author,
            'liveDetailAds' => $liveDetailAds,
        ]);
    }

public function liveBlogsAmp($cat_name, $name)
{
    $category = Category::where('site_url', $cat_name)->first();
    if (!$category) {
        return view('error');
    }

    $blog = Blog::where('site_url', $name)->with('images')->first();
    if (!$blog) {
        return view('error');
    }
    
    $author = User::where('id', $blog->author)->first();
    $liveDetailAds = Ads::where('page_type', 'details')->get()->keyBy('location');

    $liveBlogs = collect(
        LiveBlog::where('status', '1')
            ->where('blog_id', $blog->id)
            ->orderBy('created_at', 'DESC')
            ->cursor()
    );

   
    $latests = Blog::where('status', 1)
        ->where('categories_ids', $blog->categories_ids)
        ->where('id', '!=', $blog->id)
        ->whereNull('link')
        ->with(['thumbnail', 'images'])
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();

    return view('liveBlogs-amp', [
        'blogs' => $blog,
        'category' => $category,
        'liveBlogs' => $liveBlogs,
        'author' => $author,
        'liveDetailAds' => $liveDetailAds,
        'latests' => $latests, 
    ]);
}
    public function nmfvideos()
    {

        // Paginate categories where videos are marked for home page
        $paginatedCategories = Category::where('home_page_status', '1')->paginate(50);

        $videoAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        // Load videos for each paginated category
        $categoryWiseVideos = [];

        foreach ($paginatedCategories as $category) {

            $videos = Video::with('state') // eager load state
                ->where('is_active', 1)
                ->where('category_id', $category->id)
                ->orderByDesc('id')
                ->take(6)
                ->get();

            if ($videos->isNotEmpty()) {
                $categoryWiseVideos[] = [
                    'category' => $category,
                    'videos' => $videos,
                ];
            }
        }

        // Get "All" videos for swiper/top section
        $allVideos = Video::with('state')
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('allnmfVideos', compact('allVideos', 'categoryWiseVideos', 'paginatedCategories', 'videoAds'));
    }
    public function nmfvideosCategory($cat_name)
    {
        $category = Category::where('site_url', $cat_name)->first();

        if (!$category) {
            return view('error');
        }

        $videoCatAds = Ads::where('page_type', 'category')->get()->keyBy('location');

        $videos = Video::where('is_active', 1)
            ->where('category_id', $category->id)
            ->orderByDesc('id')
            ->paginate(18);

        // Attach state name if available
        $videos->transform(function ($video) {
            if ($video->state_id) {
                $state = \App\Models\State::find($video->state_id);
                $video->state_name = $state?->name ?? null;
            }
            return $video;
        });

        return view('allnmfVideosCategory', compact('videos', 'category', 'videoCatAds'));
    }
     public function biharphaseone()
    {
        return view('bihar-election-2025-phase-1');
    }
     public function biharphasetwo()
    {
        return view('bihar-election-2025-phase-2');
    }
}
