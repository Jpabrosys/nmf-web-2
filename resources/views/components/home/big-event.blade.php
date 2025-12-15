<style>
.video-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: url('{{ config('global.base_url') }}/path/to/play-icon.svg') no-repeat center center;
    background-size: contain;
    cursor: pointer;
    z-index: 10;
}

.live_tag3 {
    position: relative;
    font-weight: 700;
    display: inline-block;
    margin-right: -5px;
    padding: 6px 26px 0px 0px;
    color: #ff323b;
    border-radius: 8px;
    font-size: sans-serif;
}

.live_tag3::after {
    position: absolute;
    content: "";
    width: 11px;
    height: 11px;
    border-radius: 50%;
    right: 10.1px;
    margin-left: 5px;
    top: 10px;
    background-color: #ff1a1a;
    animation: pulse2 1s 
linear infinite;
}

.live_tag3::before
 {
    position: absolute;
    content: "";
    width: 9px;
    height: 9px;
    border-radius: 50%;
    right: 10.85px;
    top: 11px;
    background-color: #ff3737;
    z-index: 2;
}
</style>

@if ($bigEvent)
    @php
        if (!function_exists('nmf_get_video_info')) {
            function nmf_get_video_info($url)
            {
                if (!$url) return null;

                $video = ['type' => null, 'id' => null];

                // YouTube
                if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|watch\?.*?&v=|embed\/|v\/))([\w-]{11})/i', $url, $m)) {
                    $video['type'] = 'youtube';
                    $video['id'] = $m[1];
                }
                // Instagram (post, reel, tv)
                elseif (preg_match('/instagram\.com\/(?:p|reel|tv)\/([a-zA-Z0-9_-]+)/', $url, $m)) {
                    $video['type'] = 'instagram';
                    $video['id'] = $m[1];
                }
                // Facebook
                elseif (preg_match('/facebook\.com\/.*\/videos\/(\d+)/', $url, $m)) {
                    $video['type'] = 'facebook';
                    $video['id'] = $m[1];
                }
                // Direct video file
                elseif (preg_match('/\.(mp4|webm|ogg)$/i', $url)) {
                    $video['type'] = 'direct';
                    $video['id'] = $url;
                } 
		elseif (preg_match('/newsnmf\.com\/video\//', $url)) {
		    $video['type'] = 'internal';
		    $video['id'] = $url;
		}

                return $video['id'] ? $video : null;
            }
        }

        $videoInfo = isset($bigEvent->video_url) ? nmf_get_video_info($bigEvent->video_url) : null;

     	

    @endphp
    <section class="bb-banner">
        <img src="{{ big_event_banner_url($bigEvent) }}" alt="Big-event" loading="lazy">
    </section>
    <section class="big-breaking"
        style="background: linear-gradient(180deg, rgba(0, 0, 0, 0.4) 0%, #000000 100%), url('{{ config('global.base_url_big_event') . $bigEvent->background_image }}'); background-size: cover; background-position:center; background-repeat:no-repeat;">
        {{-- NL1047: 13Oct2025 banner removed from here* --}}
         @php
                   $bigblog = $bigEvent->blogs()
    ->wherePivot('sort_order', 1)
    ->first();

                  
                  

                @endphp

        <div class="cm-container">
            <div class="bigbreaking-wrap">
                <div class="bb-left">
                    <div class="cstm--card-m">
                        <div class="bb--card-m bb-media relative block">

                            @if ($videoInfo)
                                {{-- YouTube --}}
                                @if($videoInfo['type'] === 'youtube')
                                    <div class="yt-video w-100" data-id="{{ $videoInfo['id'] }}" style="position: relative;">
                                        <img src="https://img.youtube.com/vi/{{ $videoInfo['id'] }}/hqdefault.jpg" 
                                            alt="{{ $bigEvent->title }}" class="podcast--vdo" width="100%" height="auto" loading="lazy">
                                        <button class="yt-play-btn" aria-label="Play Video"><i class="fa fa-play"></i></button>
                                    </div>

                                {{-- Instagram --}}
                                @elseif($videoInfo['type'] === 'instagram')
                                    <div class="insta-video w-100" style="position: relative;">
                                        <blockquote class="instagram-media" 
                                            data-instgrm-permalink="{{ $bigEvent->video_url }}" 
                                            data-instgrm-version="14"
                                            style="margin:0 auto; width:100%; max-width:540px;">
                                            <a href="{{ $bigEvent->video_url }}" target="_blank"></a>
                                        </blockquote>

                                        <script async src="https://www.instagram.com/embed.js"></script>
                                    </div>

                                {{-- Facebook --}}
                                @elseif($videoInfo['type'] === 'facebook')
    <div class="fb-video w-100 relative" id="fb-{{ $bigEvent->id }}">
        <img src="{{ $bigEvent->video_thumb ? config('global.base_url_big_event') . $bigEvent->video_thumb : config('global.base_url_big_event') . $bigEvent->event_image }}" 
            alt="{{ $bigEvent->title }}" 
            class="podcast--vdo" width="100%" height="auto" loading="lazy">
        <span class="video-play" id="fb-play-{{ $bigEvent->id }}" 
              onclick="loadFb('{{ $bigEvent->video_url }}', '{{ $bigEvent->id }}')"></span>
    </div>

                                {{-- Direct MP4/WebM/Ogg --}}
                                @elseif($videoInfo['type'] === 'direct')
                                    <video 
                                        src="{{ $videoInfo['id'] }}" 
                                        poster="{{ $bigEvent->video_thumb ? config('global.base_url_big_event') . $bigEvent->video_thumb : config('global.base_url_big_event') . $bigEvent->event_image }}"
                                        preload="none" 
                                        playsinline 
                                        style="width:100%; height:100%; border-radius:8px;"
                                        id="video-{{ $bigEvent->id }}"
                                    ></video>

                                    <span class="video-play" aria-hidden="true" 
                                        onclick="playCustomVideo({{ $bigEvent->id }})"></span>
					
					@elseif($videoInfo['type'] === 'internal')
					    <a href="{{ $videoInfo['id'] }}" target="_blank">
					        <img 
					            src="{{ $bigEvent->video_thumb ? config('global.base_url_big_event') . $bigEvent->video_thumb : config('global.base_url_big_event') . $bigEvent->event_image }}" 
					            alt="{{ $bigEvent->title }}" 
					            style="width:100%; height:auto; border-radius:8px;">
					        <span class="video-play"></span>
					    </a>


                                @endif

                            @elseif ($bigEvent->video_path)
                                {{-- Fallback direct video --}}
                                <video 
                                    src="{{ config('global.base_url_big_event') . $bigEvent->video_path }}" 
                                    poster="{{ $bigEvent->video_thumb ? config('global.base_url_big_event') . $bigEvent->video_thumb : config('global.base_url_big_event') . $bigEvent->event_image }}"
                                    preload="none" 
                                    playsinline 
                                    style="width:100%; height:100%; border-radius:8px;"
                                    id="video-{{ $bigEvent->id }}"
                                ></video>

                                <span class="video-play" aria-hidden="true" 
                                    onclick="playCustomVideo({{ $bigEvent->id }})"></span>

                            @else
                                 @if($bigblog)
                                    @php
                                        $image_url= config('global.blog_images_everywhere')($bigblog)
                                    @endphp
                                <a href="{{ url( $bigblog->full_url) }}">
                                    <img 
                                        src="{{ $image_url }}" 
                                        alt="{{ $bigblog->name }}" 
                                        loading="lazy" 
                                        style="width:100%; height:auto; border-radius:8px;">
                                </a>
                                 @endif
                            @endif

                        </div>
                    </div>
                </div>
               
                @if($bigblog)
                  
                <div class="bb-right">
                    <span class="bb-tag inner">
                        {{  $bigEvent->tag ?? 'Big Event' }}
                    </span>
                  
                    
                   <a href="{{ url( $bigblog->full_url) }}">
                        <h1 class="bb-title">  @if ($bigblog->isLive == 1)
                                    <span class="live_tag_bigevnt">LIVE <span></span></span>
                                @endif{{ $bigblog->name }}</h1>
                    </a>

                </div>
               
                @endif
            </div>
@php
$bigEvent->load(['blogs' => function ($q) {
    $q->orderBy('big_event_blogs.sort_order', 'asc');
}]);



@endphp




            <div class="bb-bottom">
                      @php
    $allBlogs = $bigEvent->blogs;
    $bigBlog = $allBlogs->firstWhere('pivot.sort_order', 1);
    $otherBlogs = $allBlogs->filter(fn($b) => (int)$b->pivot->sort_order !== 1)
                           ->values()
                           ->take(3);
@endphp


               @foreach($otherBlogs as $blog)
                    @php
                        $image_url= config('global.blog_images_everywhere')($blog)
                    @endphp
                    <article class="bb-card">
                      

                        <a href="{{ url( $blog->full_url) }}" class="bb-link">
                            <div class="bb-img">
                                <img src="{{ asset( $image_url ) }}" 
                                    alt="{{ $blog->name }}" loading="lazy">
                            </div>
                          
                            <h5 class="bb-text">
                                @if ($blog->isLive === 1)
                                    <span class="live_tag3">LIVE <span></span></span>
                                @endif
                                {{ $blog->name }}</h5>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
	{{-- NL1038-3oct2025 : top story removed --}}
@endif

<script>
function playCustomVideo(id) {
    const video = document.getElementById(`video-${id}`);
    const playIcon = event.target;

    if (video) {
        video.play();
        playIcon.style.display = 'none'; // hide icon once playing
        video.setAttribute('controls', false);
    }
}

function loadFb(url, id) {
    const container = document.getElementById(`fb-${id}`);
    const playBtn = document.getElementById(`fb-play-${id}`);

    container.innerHTML = `
        <iframe src="https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(url)}&show_text=0&width=560&autoplay=1" 
            width="100%" height="315" style="border:none;overflow:hidden" 
            scrolling="no" frameborder="0" allowfullscreen="true"
            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
        </iframe>
    `;
    if (playBtn) playBtn.style.display = 'none';
}
</script>
