<style>
    .video-play {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: url('/path/to/play-icon.svg') no-repeat center center;
        background-size: contain;
        cursor: pointer;
        z-index: 10;
    }
</style>

@php
    use App\Models\Blog;
    use App\Models\Video;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Cache;

    // Cache Podcast block for 1 hour
$podcastData = Cache::remember('latest_podcast_widget', now()->addMinutes(60), function () {
    // --- fetch latest blog ---
    $blogPodcast = Blog::where('status', '1')
        ->whereIn('categories_ids', [23])
        ->when(request()->has('ispodcast_homepage'), function ($q) {
            $q->where('ispodcast_homepage', '1');
        })
        ->orderBy('created_at', 'DESC')
        ->first();

    // --- fetch latest video ---
    $videoPodcast = Video::where('is_active', 1)->where('category_id', 23)->orderBy('created_at', 'DESC')->first();

    // --- compare which is latest ---
    $latestPodcast = null;
    if ($blogPodcast && $videoPodcast) {
        $latestPodcast = $blogPodcast->created_at > $videoPodcast->created_at ? $blogPodcast : $videoPodcast;
    } else {
        $latestPodcast = $blogPodcast ?? $videoPodcast;
    }

    return [
        'latestPodcast' => $latestPodcast,
        'isBlog' => $latestPodcast instanceof Blog,
        'isVideo' => $latestPodcast instanceof Video,
    ];
});

$latestPodcast = $podcastData['latestPodcast'];
$isBlog = $podcastData['isBlog'];
$isVideo = $podcastData['isVideo'];

    // --- helper for YouTube ID ---
    if (!function_exists('nmf_get_youtube_id')) {
        function nmf_get_youtube_id($url)
        {
            if (!$url) {
                return null;
            }
            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|watch\?.*?&v=|embed\/|v\/))([\w-]{11})/i', $url, $m);
            return $m[1] ?? null;
        }
    }

    // --- YouTube video ID ---
    $videoId = $isBlog ? nmf_get_youtube_id($latestPodcast->link ?? '') : null;

@endphp

@if ($latestPodcast)
    <div class="news-tabs pb-0 mb-3 mt-3">
        <a class="newstab_title" href="{{ optional($latestPodcast->category)->site_url }}">
            {{ optional($latestPodcast->category)->name }}
        </a>
    </div>

    {{-- If Blog → YouTube --}}
    @if ($isBlog && $videoId)
        <div class="yt-video w-100" data-id="{{ $videoId }}" style="position: relative;">
            <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg" alt="Podcast video"
                class="podcast--vdo" width="345" height="auto" loading="lazy">

            <button class="yt-play-btn" aria-label="Play Video"><i class="fa fa-play"></i></button>
        </div>

        <div class="podcast_title">
            <h5>{{ $latestPodcast->short_title ?: $latestPodcast->name }}</h5>
        </div>

    {{-- If Video → Custom --}}
    @elseif ($isVideo)
        <div class="custom-video w-100" style="position: relative;">
            <video class="podcast--vdo" width="345" preload="none" playsinline
                id="video-{{ $latestPodcast->id }}"
                poster="{{ $latestPodcast->thumbnail_path ? config('global.base_url_videos') . $latestPodcast->thumbnail_path : '' }}"
                src="{{ config('global.base_url_videos') . $latestPodcast->video_path }}" >
            </video>

            <span class="video-play" role="button" tabindex="0" onclick="playCustomVideo({{ $latestPodcast->id }})"></span>
        </div>

        <div class="podcast_title">
            <h5>{{ $latestPodcast->title }}</h5>
        </div>
    @endif
@endif

<script src="https://www.youtube.com/iframe_api"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const wrappers = document.querySelectorAll(".yt-video");
    if (!wrappers.length) return;

    wrappers.forEach(wrapper => {
        const btn = wrapper.querySelector(".yt-play-btn");
        if (!btn) return;

        btn.addEventListener("click", function() {

            const videoId = wrapper.getAttribute("data-id");

            const iframe = document.createElement("iframe");
            iframe.width = "345";
            iframe.height = "auto";
            iframe.className = "podcast--vdo";
            iframe.frameBorder = "0";
            iframe.allowFullscreen = true;
            iframe.allow = "autoplay; encrypted-media";
            iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1&rel=0`;

            wrapper.innerHTML = "";
            wrapper.appendChild(iframe);
        });
    });
});
</script>


<script>
    function playCustomVideo(id) {
        const video = document.getElementById(`video-${id}`);
        const playIcon = event.target;

        if (video) {
            video.play();
            playIcon.style.display = 'none'; // hide icon once playing
            video.setAttribute('controls', false); // ensure controls stay hidden
        }
    }
</script>
