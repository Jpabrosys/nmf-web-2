@php
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

// Cache for AMP version
$liveBlogs = Cache::remember('breaking_news_amp', now()->addHour(), function () {
    return Blog::with('category')
        ->where('status', 1)
        ->where('breaking_status', 1)
        ->where('sequence_id', 0)
        ->whereDate('created_at', Carbon::today())
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();
});
@endphp


<div class="just_in_widget">
    <div class="just_in">

        <div class="js_title">
            <div class="js_t">LIVE</div>
        </div>

        <ul class="js_block jb">

            @foreach ($liveBlogs as $blog)
                @php
                    $blogTime = $blog->created_at->format('g:i A');
                    $todayEng = str_replace(' ', '-', date('jS F Y'));
                    $short = $blog->short_title ?: $blog->name;
                    $url = url('/breakingnews/latest-breaking-news-in-hindi-nmfnews-' . $todayEng);
                @endphp

                <li class="js_article">

                    <div class="js_left"></div>

                    <div class="js_right">
                        <p>{{ $blogTime }}</p>
                        <a href="{{ $url }}">{{ $short }}</a>
                    </div>

                </li>
            @endforeach

        </ul>

    </div>
</div>
