<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Ads;
use App\Models\Blog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class StateController extends Controller
{
    public function index()
    {
        $states = State::paginate(20);
        $states->setPath(asset('/state'));
        return view('admin/stateList')->with('states', $states);
    }

    public function add()
    {
        return view('admin/addState');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'eng_name' => 'required|string|max:255'
        ]);

        $url = $this->generateSlug($request->eng_name);

        State::create([
            'name' => $request->name,
            'eng_name' => $request->eng_name,
            'site_url' => $url,
            'home_page_status' => $request->has('home_page_status') ? 1 : 0,
            'sequence_id' => $request->sequence ?? 0,
        ]);

        return redirect('state')->with('success', 'State created successfully');
    }

    public function edit($id)
    {
        $state = State::findOrFail($id);
        return view('admin/editState')->with('state', $state);
    }

    public function editSave($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'eng_name' => 'required|string|max:255'
        ]);

        $url = $this->generateSlug($request->eng_name);

        State::where('id', $id)->update([
            'name' => $request->name,
            'eng_name' => $request->eng_name,
            'site_url' => $url,
            'home_page_status' => $request->has('home_page_status') ? 1 : 0,
            'sequence_id' => $request->sequence ?? 0,
        ]);

        return redirect('state')->with('success', 'State updated successfully');
    }

    /**
     * FIX: Improved slug generation method
     * Old clean() method had confusing logic
     */
    private function generateSlug($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        return trim($string, '-');
    }

    /**
     * DEPRECATED: This approach is bad UX
     * Use proper delete confirmation in frontend
     */
    public function del($id, Request $request)
    {
        ?>
        <script>
            if (confirm('Are You Sure You want Delete State')) {
                window.location.href = '<?php echo asset('/state/del') . '/' . $id; ?>'
            } else {
                window.location.href = '<?php echo asset('/state'); ?>'
            }
        </script>
        <?php
    }

    public function deleteState($id, Request $request)
    {
        State::where('id', $id)->delete();
        return redirect('/state')->with('success', 'State deleted successfully');
    }

    public function updateStatus(Request $request)
    {
        $state = State::find($request->state_id);

        if (!$state) {
            return response()->json([
                'success' => false, 
                'message' => 'Invalid state ID'
            ], 404);
        }

        $state->home_page_status = $request->active_status ? 1 : 0;
        $state->save();

        return response()->json(['success' => true]);
    }

    /**
     * MAJOR FIX: Optimized load more with single efficient query
     * 
     * PROBLEMS FIXED:
     * 1. Two separate queries replaced with one
     * 2. Added eager loading for relationships
     * 3. Added caching for ads
     * 4. Better error handling
     * 5. Proper offset calculation
     */
    public function stateLoadMore(Request $request, $name)
    {
        try {
            $offset = (int) $request->input('offset', 0);
            $limit = 10;

            // State lookup with caching
            $name = str_replace('_', ' ', $name);
            $state = Cache::remember("state_by_url_{$name}", 600, function () use ($name) {
                return State::where('site_url', $name)->firstOrFail();
            });

            // Cache ads for 10 minutes
            $sateAds = Cache::remember('category_page_ads', 600, function () {
                return Ads::where('page_type', 'category')
                    ->get()
                    ->keyBy('location');
            });

            /**
             * CRITICAL FIX: Single optimized query
             * Old code:
             * 1. Query 1: Get top 5 blog IDs
             * 2. Query 2: Get remaining blogs with whereNotIn
             * 
             * New code: Single query with proper offset
             */
            $blogs = Blog::where('state_ids', $state->id)
                ->where('status', 1)
                ->with([
                    'images' => function($query) {
                        $query->select('id', 'blog_id', 'file_name', 'full_path');
                    },
                    'category:id,name,site_url'
                ])
                ->orderBy('created_at', 'DESC')
                ->offset($offset + 5) // Skip initial 5 + current offset
                ->limit($limit)
                ->get();

            // If you need the first 5 separately, use this approach instead:
            // if ($offset === 0) {
            //     // For first page, skip top 5
            //     $blogs = Blog::where('state_ids', $state->id)
            //         ->where('status', 1)
            //         ->with(['images', 'category'])
            //         ->orderBy('created_at', 'DESC')
            //         ->offset(5)
            //         ->limit($limit)
            //         ->get();
            // } else {
            //     // For subsequent pages
            //     $blogs = Blog::where('state_ids', $state->id)
            //         ->where('status', 1)
            //         ->with(['images', 'category'])
            //         ->orderBy('created_at', 'DESC')
            //         ->offset($offset + 5)
            //         ->limit($limit)
            //         ->get();
            // }

            return response()->json([
                'blogs' => view('components.category.state-blog-list', [
                    'blogs' => $blogs,
                    'sateAds' => $sateAds
                ])->render(),
                'count' => $blogs->count()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('State not found in loadMore', ['name' => $name]);
            return response()->json([
                'blogs' => '', 
                'count' => 0,
                'error' => 'State not found'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('stateLoadMore error', [
                'name' => $name,
                'offset' => $offset ?? 0,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'blogs' => '', 
                'count' => 0,
                'error' => 'Internal server error'
            ], 500);
        }
    }
}