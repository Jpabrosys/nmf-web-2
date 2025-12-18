<?php
use App\Models\Blog;
use App\Models\Clip;
use Illuminate\Support\Str;
?>


{{-- <div class="news-tabs nwstb">
    <a class="newstab_title" href="{{ asset('short-videos') }}">
        <h2>शॉर्ट वीडियो</h2>
    </a>
    <a href="{{ asset('short-videos') }}">अधिक<i class="fa-solid fa-arrow-right"></i></a>
</div> --}}
<div class="shorts">
    <div class="cm-container">
        @php
            $clips = Clip::where('status', 1)->where('SortOrder', '>', 0)->with('category')->orderBy('SortOrder', 'asc')->limit(10)->get();
        @endphp

        <div class="reels-wrap">
            {{-- <img class="reels-banner" src="{{ asset('asset/images/shorts.webp') }}" alt="Shorts Preview" loading="lazy" width="300" height="300"
            decoding="async"> --}}
            @if ($clips->isNotEmpty())
                <div class="reelvideo w-100">

                    {{-- Clips view --}}
                    <div class="story-strip">
                        <div class="story-title">
                            <h2>शॉर्ट वीडियो</h2>
                            <a  href="{{ asset('short-videos') }}" class="see-more-btn2">और देखें <i
                                    class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="story-nav">
                            <button class="story-nav-prev" aria-label="पिछला">
                                <svg viewBox="0 0 24 24">
                                    <polyline points="15 6 9 12 15 18" stroke="currentColor" stroke-width="2"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <button class="story-nav-next" aria-label="अगला">
                                <svg viewBox="0 0 24 24">
                                    <polyline points="9 18 15 12 9 6" stroke="currentColor" stroke-width="2"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                       

                        <div class="swiper storySwiper">
                            <div class="swiper-wrapper">
                                @foreach ($clips as $video)
                                    @php
                                        $catUrl = optional($video->category)->site_url;
                                        $thumbUrl = '';

                                        if (
                                            $video->image_path &&
                                            $video->thumb_image &&
                                            strpos($video->image_path, 'file') !== false
                                        ) {
                                            $imagePath = str_replace('\\', '/', $video->image_path); // Normalize path slashes
                                            $folderPath = substr($imagePath, strpos($imagePath, 'file')); // Get from 'file/...'
                                            $thumbUrl = config('global.base_url_short_videos') . $folderPath . '/' . $video->thumb_image; // Build final URL
                                        }

                                        $videoUrl = asset('short-videos/' . trim($catUrl, '/') . '/' . $video->site_url);
                                    @endphp

                                    <div class="swiper-slide web_s " id="reels_s">
                                        <a href="{{ $videoUrl }}" target="_blank" class="r-story-card">
                                            <div class="story-thumb">
                                                <img src="{{ $thumbUrl }}" alt="{{ $video->title }}" loading="lazy">
                                                <span class="story-play" aria-hidden="true"></span>
                                                <p class="storyTitle">{{ $video->title }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
