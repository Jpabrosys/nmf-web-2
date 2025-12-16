  <?php  
   use App\Models\HomeSection;
    use App\Models\ElectionResult;
    use App\Models\Mahamukabla;
    use App\Models\Candidate;
    use App\Models\Party;
?>

@php
    $contentService = app(\App\Services\ContentAggregatorService::class);

    $allItems = $contentService->getAllItems($cat_id, [
        'blogs' => 10,
        'videos' => 5,
        'clips' => 5,
    ])->sortByDesc('created_at')->values()->take(20);

    // Sort and take top 20 first (to ensure we have enough blogs to choose from)
    //$allItems = $allItems->sortByDesc('created_at')->values()->take(20);

    // Left section — prefer blog, fallback to first item if none
    // $leftSection = $allItems->where('type', 'blog')->take(1);
    // if ($leftSection->isEmpty()) {
    //     $leftSection = $allItems->take(1);
    // }
    $leftSection = $allItems->take(1);

    // Exclude left section from the rest
    $remainingItems = $allItems
        ->reject(function ($item) use ($leftSection) {
            return $leftSection->contains('id', $item['id']);
        })
        ->values();

    // Limit remaining to 9 to make total = 10
    //$remainingItems = $remainingItems->take(9);

    // Split remaining into middle and right sections
    $middleSection = $remainingItems->slice(0, 4);
    $rightSection = $remainingItems->slice(4, 5);
@endphp

 @php

    $showMaha = HomeSection::where('title', 'ElectionMahaSection')->where('status', 1)->exists();
    $showLive = HomeSection::where('title', 'ElectionLiveSection')->where('status', 1)->exists();
@endphp




<div class="cm-container">
    <div class="news-tabs nwstb">
    <a class="newstab_title" href="{{ $site_url }}">
        {{-- <h2>{{ $category_name }}</h2> --}}
        <h2>{{ $category_name === 'विधानसभा चुनाव' ? 'बिहार चुनाव' : $category_name }}</h2>
    </a>
    <a href="{{ $more_url ?? $site_url }}">अधिक<i class="fa-solid fa-arrow-right"></i></a>
</div>

{{-- show mahamukabla --}}
@if ($category_name === 'विधानसभा चुनाव' && $showMaha)
    <div class="mt-3">
        @include('components.election-maha-section')
    </div>
@endif
    <div class="news-inner">
    
        <!-- Left Section -->
        @if ($leftSection->isNotEmpty())
            <div class="news-block2">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($leftSection as $item)
                            <div class="swiper-slide">
                                <div class="swiper_card">
                                    <div class="swiper_card_top">
                                        <a href="{{ $item['url'] }}">
                                            <!-- <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy"> -->
    
                                            <img 
                                                src="/file/Image/nmf-logo-full.webp" 
                                                data-src="{{ $item['image'] }}" 
                                                alt="{{ $item['title'] }}" 
                                                class="lazy-image"
                                            >
                                        </a>
                                        @if (!empty($item['category']))
                                            <div class="category_strip">
                                                <a href="{{ asset($item['category_url']) }}"
                                                    class="category">{{ $item['category'] }}</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="swiper_card_bottom">
                                        <a href="{{ $item['url'] }}">
                                            @if ($item['type'] !== 'blog')
                                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                            @elseif(!empty($item['link']))
                                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                            @endif
                                            {{ $item['title'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <!-- Left Section End -->
    
    
        <!-- Middle Section -->
        @if ($middleSection->isNotEmpty())
            <div class="news-block2">
                @foreach ($middleSection as $item)
                    <div class="custom-tab-card ctn2">
                        <a class="--ct_img" href="{{ $item['url'] }}">
                           <!--  <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy"> -->
                              <img 
                                                src="/file/Image/nmf-logo-full.webp" 
                                                data-src="{{ $item['image'] }}" 
                                                alt="{{ $item['title'] }}" 
                                                class="lazy-image"
                                            >
                        </a>
                        <div class="custom--card-title">
                            <a href="{{ $item['url'] }}">
                                @if ($item['type'] !== 'blog')
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                @elseif(!empty($item['link']))
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                @endif
                                {{ $item['title'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <!-- Middle Section End -->
    
    
        <!-- Right Section -->
        @if ($rightSection->isNotEmpty())
            <div class="news-block3">
                {{-- Blog list view --}}
                @foreach ($rightSection as $item)
                    <div class="news_desc p_2">
                        <a href="{{ $item['url'] }}">
                            @if ($item['type'] !== 'blog')
                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                            @elseif(!empty($item['link']))
                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                            @endif
                            {{ $item['title'] }}
                        </a>
                    </div>
                @endforeach
    
            </div>
        @endif
        <!-- Right Section End -->
    
    </div>


</div>