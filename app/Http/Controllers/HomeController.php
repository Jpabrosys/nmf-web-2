<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

// Models
use App\Models\HomeSection;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Subscriber;
use App\Models\Vote;
use App\Models\VoteOption;
use App\Models\TrendingTag;
use App\Models\Ads;
use App\Models\Rashifal;
use App\Models\WebStories;
use App\Models\BigEvent;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('admin/dashboard');
    }

    /**
     * Optimized Home Page Function
     * Caches all data for 10 minutes to reduce DB load
     */
    public function homePage()
    {
        // Cache key: homepage_data_v1
        // Duration: 600 seconds (10 minutes)
        $data = Cache::remember('homepage_data_v1', 600, function () {
            return $this->getHomePageData();
        });

        return response()
            ->view('welcome', ['data' => $data])
            ->header('Cache-Control', 'public, max-age=600, s-maxage=1800');
    }

    /**
     * Gather all data in a single function for caching
     */
    private function getHomePageData()
    {
        // 1. Fetch All Home Sections (Single Query)
        $allSections = HomeSection::with('category')
            ->where('status', 1)
            ->orderBy('section_order', 'asc')
            ->orderBy('sidebar_sec_order', 'asc')
            ->get();

        // 2. Separate logic for Banners, Sidebar, and Main Sections
        $tempSections = [];
        $sidebarCategoriesList = [];
        $bannerData = [
            'bannerimgurl' => null,
            'bannerlinkurl' => '#',
            'bannermobileimgurl' => null,
            'bannermobilelinkurl' => '#'
        ];

        foreach ($allSections as $section) {
            // Main Sections
            if ($section->type === 'section') {
                $order = (int) $section->section_order;
                $tempSections[$order] = [
                    'catid' => $section->catid,
                    'name' => optional($section->category)->name ?? '',
                    'site_url' => optional($section->category)->site_url ?? '',
                ];
            }
            // Sidebar
            elseif ($section->type === 'sidebar' && !empty($section->category)) {
                $order = (int) $section->sidebar_sec_order;
                $sidebarCategoriesList[$order] = [
                    'catid' => $section->catid,
                    'name' => $section->category->name,
                    'site_url' => $section->category->site_url,
                ];
            }
            // Banners
            elseif ($section->type === 'banner') {
                $title = strtolower($section->title);
                if ($title === 'banner') {
                    $bannerData['bannerimgurl'] = $section->image_url;
                    $bannerData['bannerlinkurl'] = $section->banner_link ?? '#';
                } elseif ($title === 'bannermobile') {
                    $bannerData['bannermobileimgurl'] = $section->image_url;
                    $bannerData['bannermobilelinkurl'] = $section->banner_link ?? '#';
                }
            }
        }

        // 3. Process Section Logic (Extract Rajya/Vidhan Sabha & Reindex)
        $collection = collect($tempSections);
        
        $rajyaSection = $collection->firstWhere('name', 'राज्य');
        $bidhanSabhaSection = $collection->firstWhere('name', 'विधान सभा चुनाव');

        // Remove special sections and reindex starting from 1
        $finalSectionCategories = $collection
            ->reject(fn($item) => in_array($item['name'], ['राज्य', 'विधान सभा चुनाव']))
            ->values()
            ->mapWithKeys(function ($item, $index) {
                return [$index + 1 => $item];
            })
            ->toArray();

        // Reindex Sidebar starting from 1
        $finalSidebarList = collect($sidebarCategoriesList)
            ->values()
            ->mapWithKeys(function ($item, $index) {
                return [$index + 1 => $item];
            })
            ->toArray();

        // 4. Fetch Breaking News (Optimized)
        $breakingNews = Blog::where('status', 1)
            ->where('breaking_status', 1)
            ->where('sequence_id', 0)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'DESC')
            ->select('name', 'created_at') // Optimize: select only needed fields
            ->first();

        // 5. Fetch Big Event
        $showBigEvent = HomeSection::where('title', 'DisplayBigEvent')->where('status', 1)->exists();
        $bigEventData = null;
        if ($showBigEvent) {
            $bigEventData = BigEvent::where('is_active', 1)
                ->with(['blogs' => function($query) {
                    $query->latest()->take(3);
                }])
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // 6. Fetch Web Stories
        $webStories = WebStories::with(['category', 'webStoryFiles'])
            ->where('status', '1')
            ->orderBy('sequence', 'asc')
            ->limit(10)
            ->get();

        // 7. Fetch Rashifal
        $rashifal = Rashifal::where('status', 1)->get();

        // 8. Fetch Other Data (Ads, Tags, Polls)
        $homeAds = Ads::where('page_type', 'home')->get()->keyBy('location');
        
        $trendingTags = TrendingTag::where('status', 1)
            ->orderBy('sequence_id', 'asc')
            ->pluck('name')
            ->toArray();
            
        $pollData = $this->getLatestPollData();
        
        // 9. Flags for conditional rendering
        $flags = [
            'showMaha' => HomeSection::where('title', 'ElectionMahaSection')->where('status', 1)->exists(),
            'showLive' => (int) (HomeSection::where('title', 'ElectionLiveSection')->value('status') ?? 0),
            'showExitpoll' => (int) (HomeSection::where('title', 'ExitPollSection')->value('status') ?? 0),
            'showBigEvent' => $showBigEvent,
            'showVoteInTopNews' => HomeSection::where('title', 'ShowVoteInTop')->where('status', 1)->exists(),
            'showBannerAboveTopNews' => HomeSection::where('title', 'ShowBannerAboveTopStory')->where('status', 1)->exists(),
        ];

        return [
            'sectionCategories' => $finalSectionCategories,
            'rajyaSection'      => $rajyaSection,
            'bidhanSabhaSection'=> $bidhanSabhaSection,
            'sidebarCategories' => $finalSidebarList,
            'banners'           => $bannerData,
            'breakingNews'      => $breakingNews,
            'bigEvent'          => $bigEventData,
            'webStories'        => $webStories,
            'rashifal'          => $rashifal,
            'uniqueTags'        => $trendingTags,
            'homeAds'           => $homeAds,
            'voteData'          => $pollData,
            'flags'             => $flags,
        ];
    }

    /**
     * Optimized Poll Data Fetching
     */
    private function getLatestPollData()
    {
        $latestPoll = Vote::with(['options' => function($query) {
                $query->select('id', 'vote_id', 'name', 'vote_count');
            }])
            ->latest()
            ->first();

        if (!$latestPoll) {
            return ['title' => 'Default Question?', 'options' => [], 'total' => 0, 'id' => null];
        }

        $allVotes = $latestPoll->options->pluck('vote_count', 'name')->toArray();
        $totalVotes = array_sum($allVotes);

        return [
            'title' => $latestPoll->title,
            'options' => $allVotes,
            'total' => $totalVotes,
            'id' => $latestPoll->id,
        ];
    }
    
    /**
     * Vote Saving Logic with Transaction
     */
    public function savedVote($id, Request $request)
    {
        $request->validate([
            'vote_id' => 'required|integer|exists:votes,id',
            'option_id' => 'required|integer|exists:vote_options,id',
            'title' => 'required|string',
        ]);

        $userIp = $request->ip();

        $alreadyVoted = Vote::where('id', $id)
            ->where('title', $request->title)
            ->where('user_ip', $userIp)
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'status' => 'error',
                'already_voted' => true,
                'message' => 'आप वोट कर चुके हैं'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $updated = VoteOption::where('vote_id', $request->vote_id)
                ->where('id', $request->option_id)
                ->increment('vote_count');

            if (!$updated) {
                throw new \Exception('Option not found');
            }

            Vote::where('id', $id)
                ->whereNull('user_ip')
                ->update([
                    'user_ip' => $userIp,
                    'updated_at' => now()
                ]);

            DB::commit();
            
            // Clear homepage cache so the new vote count shows up eventually
            Cache::forget('homepage_data_v1');

            $results = VoteOption::where('vote_id', $request->vote_id)->pluck('vote_count', 'id');
            $totalVotes = $results->sum();

            return response()->json([
                'status' => 'success',
                'message' => 'Vote recorded',
                'results' => $results,
                'totalVotes' => $totalVotes,
                'voteId' => $request->vote_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vote failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to record vote'], 500);
        }
    }

    public function getVoteResults($id)
    {
        // Short cache for results to prevent spamming DB
        $results = Cache::remember("vote_results_{$id}", 60, function () use ($id) {
            $options = VoteOption::where('vote_id', $id)->pluck('vote_count', 'id');
            return ['results' => $options->toArray(), 'totalVotes' => $options->sum()];
        });

        return response()->json([
            'status' => 'success',
            'results' => $results['results'],
            'totalVotes' => $results['totalVotes']
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:subscribers,email']);
        Subscriber::create(['email' => $request->email]);
        return redirect('/')->with('subscribemessage', "Thanks for subscribing!");
    }

    public function handlePost(Request $request)
    {
        if ($request->_action === 'vote') {
            return $this->savedVote($request->vote_id, $request);
        } elseif ($request->_action === 'subscribe') {
            return $this->subscribe($request);
        }
        abort(400, 'Invalid form action.');
    }
    
    // Toggle Functions (Clear Cache on Update)
    
    private function clearHomePageCache()
    {
        Cache::forget('homepage_data_v1');
    }
    
    public function toggleMahaSection()
    {
        $section = HomeSection::firstOrCreate(
            ['title' => 'ElectionMahaSection'],
            ['status' => 0, 'section_order' => 0]
        );
        $section->status = $section->status == 1 ? 0 : 1;
        $section->save();
        $this->clearHomePageCache();
        
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        return redirect(config('global.base_url').'election/mahamukabla/show')->with('success', 'Updated!');
    }

    public function toggleLiveSection()
    {
        $section = HomeSection::firstOrCreate(
            ['title' => 'ElectionLiveSection'],
            ['status' => 0, 'section_order' => 0]
        );
        $section->status = $section->status == 1 ? 0 : 1;
        $section->save();
        $this->clearHomePageCache();
        
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        return redirect(config('global.base_url').'election/manage-vote-count')->with('success', 'Updated!');
    }

    public function toggleExitPoll()
    {
        $section = HomeSection::firstOrCreate(
            ['title' => 'ExitPollSection'],
            ['status' => 0, 'section_order' => 0]
        );
        $section->status = $section->status == 1 ? 0 : 1;
        $section->save();
        $this->clearHomePageCache();
        
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        return redirect(config('global.base_url').'election/exit-poll')->with('success', 'Updated!');
    }

    /**
     * Helper method to get the home page data (cached or fresh).
     * This avoids repeating the Cache::remember logic.
     */
    private function getHomeDataFromCacheOrFresh()
    {
        return Cache::remember('homepage_data_v1', 600, function () {
            return $this->getHomePageData();
        });
    }

/**
 * Handles the AJAX request for lazy loading a single section.
 * @param string $section_id The identifier for the section
 * @return \Illuminate\Http\Response
 */
public function lazyLoadSection(Request $request, $section_id)
{
    // Debug logging
    \Log::info('Lazy load request received', [
        'section_id' => $section_id,
        'url' => $request->fullUrl()
    ]);

    try {
        // 1. Retrieve ALL home data from the cache (or refresh if expired)
        $data = $this->getHomeDataFromCacheOrFresh();

        $sectionCategories = $data['sectionCategories'];
        $sidebarCategoriesList = $data['sidebarCategories'];
        $rajyaSection = $data['rajyaSection'];
        $bidhanSabhaSection = $data['bidhanSabhaSection'];
        $homeAds = $data['homeAds'];
        $rashifal = $data['rashifal'];
        $flags = $data['flags'];
        
        // 2. Determine which section to render
        switch ((string) $section_id) {
            case 'reels':
                \Log::info('Loading reels section');
                // Return a simple test if view doesn't exist
                if (view()->exists('components.reels-section')) {
                    return response(view('components.reels-section')->render())
                        ->header('Content-Type', 'text/html');
                }
                return response('<div class="p-3">Reels component exists here</div>')
                    ->header('Content-Type', 'text/html');

            case 'video':
                \Log::info('Loading video section');
                if (view()->exists('components.video-gallery-allcat')) {
                    return response(view('components.video-gallery-allcat')->render())
                        ->header('Content-Type', 'text/html');
                }
                return response('<div class="p-3">Video component exists here</div>')
                    ->header('Content-Type', 'text/html');

            // Sections that use slider-two-news-5
            case '4':
            case '6':
            case '8':
            case '14':
                \Log::info('Loading section', ['section' => $section_id]);
                $section = $sectionCategories[(int)$section_id] ?? null;
                
                if (!$section) {
                    \Log::warning('Section not found', ['section_id' => $section_id]);
                    return response('<div class="p-3">Section not configured</div>', 200)
                        ->header('Content-Type', 'text/html');
                }

                $output = view('components.slider-two-news-5', [
                    'cat_id' => $section['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $section['site_url'],
                    'category_name' => $section['name'],
                ])->render();
                
                // Special case: Rashifal Section embedded after Section 4
                if ($section_id == '4' && !empty($rashifal)) {
                    if (view()->exists('components.lazy-loaded.rashifal-block')) {
                        $rashifalHtml = view('components.lazy-loaded.rashifal-block', compact('rashifal'))->render();
                        $output .= $rashifalHtml;
                    }
                }

                return response($output)->header('Content-Type', 'text/html');
            
            // Sections that use news-nine-style
            case '3':
            case '5':
            case '7':
                \Log::info('Loading news-nine-style section', ['section' => $section_id]);
                $section = $sectionCategories[(int)$section_id] ?? null;
                
                if (!$section) {
                    return response('<div class="p-3">Section not configured</div>', 200)
                        ->header('Content-Type', 'text/html');
                }

                return response(view('components.news-nine-style', [
                    'cat_id' => $section['catid'],
                    'cat_name' => $section['name'],
                    'cat_site_url' => $section['site_url'],
                    'rightTitle' => ($section_id == '7') ? 'वीडियो' : null,
                ])->render())->header('Content-Type', 'text/html');
            
            case 'state-tabs':
                \Log::info('Loading state-tabs section');
                if (view()->exists('components.lazy-loaded.state-tabs-block')) {
                    return response(view('components.lazy-loaded.state-tabs-block', [
                        'rajyaSection' => $rajyaSection,
                        'bidhanSabhaSection' => $bidhanSabhaSection,
                        'homeAds' => $homeAds,
                    ])->render())->header('Content-Type', 'text/html');
                }
                
                // Fallback inline version
                $html = '<div class="cm-container"><div class="news_tab_row"><div class="_devider"><div class="left_content news_tabs">';
                
                if (!empty($rajyaSection)) {
                    $html .= view('components.all-states-tab', [
                        'cat_id' => $rajyaSection['catid'],
                        'cat_name' => $rajyaSection['name'],
                        'cat_site_url' => $rajyaSection['site_url'],
                    ])->render();
                }
                
                if (!empty($bidhanSabhaSection)) {
                    $html .= view('components.bidhansabha-states-tab', [
                        'cat_id' => $bidhanSabhaSection['catid'],
                        'cat_name' => $bidhanSabhaSection['name'],
                        'cat_site_url' => $bidhanSabhaSection['site_url'],
                    ])->render();
                }
                
                $html .= '</div></div></div></div>';
                return response($html)->header('Content-Type', 'text/html');
                
            case 'middle-news-area':
                \Log::info('Loading middle-news-area section');
                if (view()->exists('components.lazy-loaded.middle-news-area-block')) {
                    return response(view('components.lazy-loaded.middle-news-area-block', [
                        'sectionCategories' => $sectionCategories,
                        'sidebarCategoriesList' => $sidebarCategoriesList,
                        'homeAds' => $homeAds,
                        'flags' => $flags,
                    ])->render())->header('Content-Type', 'text/html');
                }
                return response('<div class="p-3">Middle news area component missing</div>')
                    ->header('Content-Type', 'text/html');
                
            case 'bottom-dynamic':
                \Log::info('Loading bottom-dynamic section');
                if (view()->exists('components.lazy-loaded.bottom-dynamic-block')) {
                    return response(view('components.lazy-loaded.bottom-dynamic-block', [
                        'sectionCategories' => $sectionCategories,
                        'homeAds' => $homeAds,
                    ])->render())->header('Content-Type', 'text/html');
                }
                return response('<div class="p-3">Bottom dynamic component missing</div>')
                    ->header('Content-Type', 'text/html');
                
            default:
                \Log::warning('Unknown section ID requested', ['section_id' => $section_id]);
                return response('<div class="p-3">Unknown section</div>', 200)
                    ->header('Content-Type', 'text/html');
        }

    } catch (\Exception $e) {
        \Log::error("Lazy load failed for section $section_id", [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response(
            '<div class="alert alert-danger m-3">Error: ' . $e->getMessage() . '</div>', 
            500
        )->header('Content-Type', 'text/html');
    }
}

}