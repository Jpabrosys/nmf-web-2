


<?php
use App\Models\Clip;
use Illuminate\Support\Str;
?>

<div class="web-stories-block" style="padding-top:20px;">
    <div class="cm-container">
        <div class="web-stories">

            @php
                $clips = Clip::where('status',1)
                    ->where('SortOrder','>',0)
                    ->with('category')
                    ->orderBy('SortOrder','asc')
                    ->limit(10)
                    ->get();
            @endphp

            @if ($clips->isNotEmpty())

            <div class="story-title">
                <h2>शॉर्ट वीडियो</h2>
                <a href="{{ asset('short-videos') }}" class="see-more-btn2">
                    और देखें <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="stories-container">
                <div class="swiper-wrapper-outer">

                    <div class="swiper swp-unique-shorts">
                        <div class="swiper-wrapper">
                            @foreach ($clips as $video)
                                @php
                                    $catUrl = optional($video->category)->site_url;
                                    $thumbUrl = '';

                                    if ($video->image_path && $video->thumb_image && str_contains($video->image_path,'file')) {
                                        $path = str_replace('\\','/',$video->image_path);
                                        $folder = substr($path, strpos($path,'file'));
                                        $thumbUrl = config('global.base_url_short_videos') . $folder . '/' . $video->thumb_image;
                                    }

                                    $videoUrl = asset('short-videos/' . trim($catUrl,'/') . '/' . $video->site_url);
                                @endphp

                                <div class="swiper-slide">
                                    <a href="{{ $videoUrl }}" target="_blank" class="shorts-card-unique">
                                        <img src="{{ $thumbUrl }}" alt="{{ $video->title }}" loading="lazy">

                                        <div class="play-icon-unique">
                                            <i class="fas fa-play"></i>
                                        </div>

                                        <div class="title-overlay-unique">
                                            {{ Str::limit($video->title, 40) }}
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- <div class="nav-unique-prev"><i class="fas fa-chevron-left"></i></div>
                    <div class="nav-unique-next"><i class="fas fa-chevron-right"></i></div> --}}

                </div>
            </div>
            @endif

        </div>
    </div>
</div>


