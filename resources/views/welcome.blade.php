@extends('layouts.app')
@section('content')
    <?php
    use App\Models\Blog;
    use App\Models\Category;
    use App\Models\User;
    use Carbon\Carbon;
    use App\Models\HomeSection;
    use App\Models\LiveBlog;
    use App\Models\WebStories;
    use App\Models\BigEvent;
    
    use App\Models\ElectionResult;
    use App\Models\MahaMukabla;
    use App\Models\Candidate;
    use App\Models\Party;
    
    $class = '';
    $videorow = 0;
    $videobutrow = 0;

    $sectionCategories = [];
    $sidebarCategoriesList = [];
    $bannerList = [];
    $bannerimgurl = null;
    $bannerlinkurl = '#';
    $bannermobileimgurl = null;
    $bannermobilelinkurl = '#';

    //Section categories
    if (!empty($data['homeSections'])) {
        foreach ($data['homeSections'] as $section) {
            $title = strtolower($section->title);

            // Section category mapping

            $sectionOrder = (int) $section->section_order;
            $sectionCategories[$sectionOrder] = [
                'catid' => $section->catid,
                'name' => optional($section->category)->name ?? '',
                'site_url' => optional($section->category)->site_url ?? '',
            ];
        }

        // Extract special sections (राज्य and विधान सभा चुनाव) and remove from list
        $rajyaSection = collect($sectionCategories)->firstWhere('name', 'राज्य');
        $bidhanSabhaSection = collect($sectionCategories)->firstWhere('name', 'विधान सभा चुनाव');

        $sectionCategories = collect($sectionCategories)
            ->reject(fn($section) => in_array($section['name'], ['राज्य', 'विधान सभा चुनाव']))
            ->values() // reindex starting from 0
            ->mapWithKeys(function ($item, $index) {
                return [$index + 1 => $item]; // start from 1
            })
            ->toArray();

        //dd($sectionCategories);
    }

    // Sidebar categories
    if (!empty($data['sidebarCategories'])) {
        foreach ($data['sidebarCategories'] as $sidebarSection) {
            $sectionOrder = (int) $sidebarSection->sidebar_sec_order;

            if (!empty($sidebarSection->category)) {
                $sidebarCategoriesList[$sectionOrder] = [
                    'catid' => $sidebarSection->catid,
                    'name' => $sidebarSection->category->name,
                    'site_url' => $sidebarSection->category->site_url,
                ];
            }
        }

        // Reindex starting from 1
        $sidebarCategoriesList = collect(array_values($sidebarCategoriesList))
            ->mapWithKeys(function ($item, $index) {
                return [$index + 1 => $item];
            })
            ->toArray();
    }

    // Banners
    if (!empty($data['banners'])) {
        foreach ($data['banners'] as $bannerSection) {

            $title = strtolower($bannerSection->title);

            if ($title === 'banner') {
                $bannerimgurl = $bannerSection->image_url;
                $bannerlinkurl = $bannerSection->banner_link ?? '#';
            } elseif ($title === 'bannermobile') {
                $bannermobileimgurl = $bannerSection->image_url;
                $bannermobilelinkurl = $bannerSection->banner_link ?? '#';
            }
        }
    }

    ?>

    <?php
  $latest_blog = Blog::with('category')
  ->where('status', 1)
  ->where('breaking_status', 1)
  ->where('sequence_id', 0)
  ->whereDate('created_at', Carbon::today())
  ->orderBy('created_at', 'DESC')
  ->first();
  if(isset($latest_blog)){
    $blogname= $latest_blog->name;
?>


    <div class="brk-m">
        <div class="cm-container">
            <div class="breaking-news">
                <div class="brk-news-wrap ">
                    <div class="brk-l">
                        <h4>
                            <div class="breaking-bars"><span></span><span></span><span></span><span></span></div>Breaking
                            News
                        </h4>
                    </div>
                    <div class="brk-r">
                        <?php
                        $todayEng = str_replace(' ', '-', date('jS F Y')); // e.g., 5th-May-2025
                        ?>
                        <a class="brk-link"
                            href="{{ config('global.base_url').('breakingnews/latest-breaking-news-in-hindi-nmfnews-') }}{{ $todayEng }}">
                            {{ $blogname }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

    @if (!empty($data['uniqueTags']) && count(array_filter($data['uniqueTags'])))
<div class="swiper-tags-container">
    <div class="swiper swiper-tags-main">
        <div class="gradient-left"></div>
        <div class="gradient-right"></div>

        <div class="swiper-wrapper swiper-tags-wrapper">
            @php
                $baseUrl = config('global.base_url');
            @endphp

            @foreach ($data['uniqueTags'] as $tag)
                @if (trim($tag) !== '')
                    <a href="{{ rtrim($baseUrl, '/') }}/search?search={{ urlencode($tag) }}"
                       class="swiper-slide swiper-tag">{{ $tag }}</a>
                @endif
            @endforeach
        </div>

        <!-- Navigation buttons -->
        <div class="swiper-tags-button-prev">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="swiper-tags-button-next">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
</div>

    @endif


@php
    $showMaha = HomeSection::where('title', 'ElectionMahaSection')->where('status', 1)->exists();
   
    $showLive = HomeSection::where('title', 'ElectionLiveSection')->value('status') ?? 0;
   $showExitpoll = HomeSection::where('title', 'ExitPollSection')->value('status') ?? 0;

    $showBigEvent = HomeSection::where('title', 'DisplayBigEvent')->where('status', 1)->value('status') ?? false;
@endphp




@if ($showExitpoll==1)
    {{-- Show Exit Poll when Live is off --}}

    @include('components.election-exit-poll')

    @if ($showBigEvent)
        <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />
    @endif
@elseif ($showLive==1)
    {{--  Live has highest priority --}}

    @include('components.election-live-section')
      @if ($showBigEvent)
        <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />
    @endif
@endif

    
    @php
        $showVoteInTopNews = HomeSection::where('title', 'ShowVoteInTop')
            ->where('status', 1)
            ->value('status') ?? false;
    @endphp

    @if ($showBigEvent)
        @php
            $bigEvent = BigEvent::where('is_active', 1)
                ->with(['blogs' => function($query) {
                    $query->latest()->take(3);
                }])
                ->orderBy('created_at', 'desc')
                ->first();
        @endphp
        @include('components.home.big-event', [
            'bigEvent' => $bigEvent,
        ])

        {{-- Horizontal-1 Advertise --}}
    @endif
        {{-- Horizontal-1 Advertise --}}
            <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />

            @php $showBannerAboveTopNews = HomeSection::where('title', 'ShowBannerAboveTopStory')
                ->where('status', 1)
                ->value('status') ?? false;
            @endphp

            {{-- Banner Section --}}
            @if ($showBannerAboveTopNews)
                @include('components.home.banner-section', [
                    'bannerimgurl' => $bannerimgurl,
                    'bannerlinkurl' => $bannerlinkurl,
                    'bannermobileimgurl' => $bannermobileimgurl,
                    'bannermobilelinkurl' => $bannermobilelinkurl,
            ])
            @endif

            
            {{-- Top News Section --}}
            <section class="top--news">
                @include('components.home.top-news-section', [
                    'showVoteInTopNews' => $showVoteInTopNews
                ])
            </section>

            {{-- Banner Section --}}
            @if (!$showBannerAboveTopNews)
                @include('components.home.banner-section', [
                    'bannerimgurl' => $bannerimgurl,
                    'bannerlinkurl' => $bannerlinkurl,
                    'bannermobileimgurl' => $bannermobileimgurl,
                    'bannermobilelinkurl' => $bannermobilelinkurl,
                ])
            @endif
   


    <div id="appDownloadModal">
        <div class="app-download-modal">
            <img class="modal-img" src="{{ config('global.base_url_asset') }}asset/images/modal.webp" alt="app-image">
            <button class="modal-close-button" onclick="closeModal()">×</button>
            <h2>Download Our App</h2>
            <p>Get the best experience by downloading our mobile app!</p>
            <div class="app_btn_wrap justify-content-center">
                <a href="https://www.newsnmf.com/nmfapps/" class="playstore-button">
                    
                    <span class="texts">
                        <span class="text-2">
                            Download the App
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="news-panel">
        <div class="cm-container">
            @if (!empty($sectionCategories[1]))
                @include('components.slider-two-news-5', [
                    'cat_id' => $sectionCategories[1]['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $sectionCategories[1]['site_url'],
                    'category_name' => $sectionCategories[1]['name'],
                ])
            @endif

            {{-- Horizontal-2 Advertise --}}
            <x-horizontal-ad :ad="$data['homeAds']['home_below_news_section_ad'] ?? null" />

        </div>
    </div>

<section class="custom_block">
    @if (!empty($sectionCategories[5]))
        @include('components.news-nine-style', [
            'cat_id'        => $sectionCategories[5]['catid'],
            'cat_name'      => $sectionCategories[5]['name'],
            'cat_site_url'  => $sectionCategories[5]['site_url'],
        ])
    @endif
 
    <div class="rasifal-section">
        <div class="cm-container">
 
            <div class="rashifal-section">
                <div class="rashifal-container" role="region" aria-label="राशिफल दैनिक">
                                    <div class="rashifal-box">
 
                    <div class="rotating-bg" aria-hidden="true"></div>
 
                    <?php
                        $rashis = App\Models\Rashifal::where('status', 1)->get();
                    ?>
 
                    <div class="rashifal-wrapper">
 
                        <!-- Navigation Buttons -->
                        <button class="nav-btn prev" aria-label="पिछला राशिफल">
                            <i class="fas fa-chevron-left"></i>
                        </button>
 
                        <div class="rashifal-slider"
                             tabindex="0"
                             role="listbox"
                             aria-live="polite"
                             aria-label="राशि चिन्ह">
 
                            <div class="rashifal-item spacer" aria-hidden="true"></div>
 
                            @foreach($rashis as $index => $r)
                                <?php
                                    $rashiImg = config('global.base_url_image') . $r->full_path . '/' . $r->file_name;
                                ?>
                                <div class="rashifal-item" role="option"
                                     aria-selected="{{ $index == 0 ? 'true':'false' }}">
                            <img src="{{ $rashiImg }}"
                                alt="{{ $r->name }}"
                                data-sign="{{ strtolower($r->name) }}"
                                data-title="{{ $r->name }}"
                                data-description="{{ $r->description }}"
                                class="{{ $index == 0 ? 'active':'' }}"
                                loading="lazy"
                                tabindex="0" />
                                </div>
                            @endforeach
 
                            <div class="rashifal-item spacer" aria-hidden="true"></div>
 
                        </div>
 
                        <!-- Navigation Buttons -->
                        <button class="nav-btn next" aria-label="अगला राशिफल">
                            <i class="fas fa-chevron-right"></i>
                        </button>
 
                    </div>
 
                    <!-- Dynamic Title -->
                    <h2 id="rashifal-title">
                        आपके तारे - दैनिक: {{ $rashis[0]->name ?? '' }}
                    </h2>
 
                    <!-- Dynamic Description -->
                    <p id="rashifal-text">
                        {{ $rashis[0]->description ?? '' }}
                    </p>
 
                </div>
 
                <div class="adBgSidebar">
                    <div class="adtxt">Advertisement</div>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3986924419662120"
     data-ad-slot="6911924096"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
                </div>
 
                </div>
 
            </div>
 
        </div>
    </div>
</section>
    <div class="web-stories-section">
        <?php
        $allWebStories = WebStories::with('category', 'webStoryFiles')->where('status', '1')->orderBy('sequence', 'asc')->limit(10)->get();
        ?>
        @include('components.webstory', [
            'webStories' => $allWebStories,
        ])
        {{-- @include('components.webstory') --}}
    </div>

    <div class="news-panel">
        <div class="cm-container">
            @if (!empty($sectionCategories[2]))
                @include('components.slider-two-news-5', [
                    'cat_id' => $sectionCategories[2]['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $sectionCategories[2]['site_url'],
                    'category_name' => $sectionCategories[2]['name'],
                ])
            @endif
        </div>
    </div>


    <div class="news-panel reels">
                @include('components.reels-section')
    </div>

    <section class="custom_block">
        @if (!empty($sectionCategories[3]))
            @include('components.news-nine-style', [
                'cat_id' => $sectionCategories[3]['catid'],
                'cat_name' => $sectionCategories[3]['name'],
                'cat_site_url' => $sectionCategories[3]['site_url'],
            ])
        @endif
    </section>

    <section class="video-section">
        @include('components.video-gallery-allcat')
    </section>

    {{-- Horizontal-3 Advertise --}}
    <x-horizontal-ad :ad="$data['homeAds']['home_below_video_section_ad'] ?? null" />

    {{-- <section class="photo_section">
        <div class="cm-container photo_block">
            <a href="{{ asset('photos') }}" class="vdo_title">फोटो गैलरी</a>
            @include('components.photo-gallery-12')
        </div>
    </section> --}}
    <div class="news-panel">
        <div class="cm-container">
            @if (!empty($sectionCategories[4]))
                @include('components.slider-two-news-5', [
                    'cat_id' => $sectionCategories[4]['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $sectionCategories[4]['site_url'],
                    'category_name' => $sectionCategories[4]['name'],
                ])
            @endif
        </div>
    </div>


 


    <div class="div_row mb-3">
        <div class="cm-container">
            <div class="news_tab_row">
                <div class="_devider">
                    <!-- States Component -->
                    <div class="left_content news_tabs">
                        @if (!empty($rajyaSection))
                            @include('components.all-states-tab', [
                                'cat_id' => $rajyaSection['catid'],
                                'cat_name' => $rajyaSection['name'],
                                'cat_site_url' => $rajyaSection['site_url'],
                            ])
                        @endif

                        {{-- Horizontal-4 Advertise --}}
                        <x-horizontal-ad :ad="$data['homeAds']['home_below_state_section_ad'] ?? null" />

                        @if (!empty($bidhanSabhaSection))
                            @include('components.bidhansabha-states-tab', [
                                'cat_id' => $bidhanSabhaSection['catid'],
                                'cat_name' => $bidhanSabhaSection['name'],
                                'cat_site_url' => $bidhanSabhaSection['site_url'],
                            ])
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <section class="news-panel">
        <div class="cm-container">
            @if (!empty($sectionCategories[6]))
                @include('components.slider-two-news-5', [
                    'cat_id' => $sectionCategories[6]['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $sectionCategories[6]['site_url'],
                    'category_name' => $sectionCategories[6]['name'],
                ])
            @endif
        </div>
    </section>
    <section class="custom_block">
        @if (!empty($sectionCategories[7]))
            @include('components.news-nine-style', [
                'cat_id' => $sectionCategories[7]['catid'],
                'cat_name' => $sectionCategories[7]['name'],
                'cat_site_url' => $sectionCategories[7]['site_url'],
                'rightTitle' => 'वीडियो',
            ])
        @endif
    </section>
    <section class="news-panel">
        <div class="cm-container">
            @if (!empty($sectionCategories[8]))
                @include('components.slider-two-news-5', [
                    'cat_id' => $sectionCategories[8]['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $sectionCategories[8]['site_url'],
                    'category_name' => $sectionCategories[8]['name'],
                ])
            @endif
        </div>
    </section>
    <div class="middle-news-area news-area">
        <div class="cm-container">
            <div class="left_and_right_layout_divider">
                <div class="lay_row">
                    <div class="cm-col-lg-8 cm-col-12 sticky_portion px-0">
                        <div id="primary" class="content-area">
                            <main id="main" class="site-main">
                                @if (!empty($sectionCategories[9]))
                                    @include('components.slider-one-news-5', [
                                        'cat_id' => $sectionCategories[9]['catid'],
                                        'cat_name' => $sectionCategories[9]['name'],
                                        'cat_site_url' => $sectionCategories[9]['site_url'],
                                    ])
                                @endif

                                {{-- Horizontal-Small-1 Advertise --}}
                                <x-horizontal-sm-ad :ad="$data['homeAds']['home_middle_horz_sm_ad1'] ?? null" />

                                @if (!empty($sectionCategories[10]))
                                    @include('components.slider-one-news-5', [
                                        'cat_id' => $sectionCategories[10]['catid'],
                                        'cat_name' => $sectionCategories[10]['name'],
                                        'cat_site_url' => $sectionCategories[10]['site_url'],
                                    ])
                                @endif

                                {{-- being ghumakad --}}
                                @if (!empty($sectionCategories[11]))
                                    @include('components.photo-slider', [
                                        'cat_id' => $sectionCategories[11]['catid'],
                                        'cat_name' => $sectionCategories[11]['name'],
                                        'cat_site_url' => $sectionCategories[11]['site_url'],
                                    ])
                                @endif

                                @if (!empty($sectionCategories[12]))
                                    @include('components.slider-one-news-5', [
                                        'cat_id' => $sectionCategories[12]['catid'],
                                        'cat_name' => $sectionCategories[12]['name'],
                                        'cat_site_url' => $sectionCategories[12]['site_url'],
                                    ])
                                @endif

                                {{-- Horizontal-Small-2 Advertise --}}
                                <x-horizontal-sm-ad :ad="$data['homeAds']['home_middle_horz_sm_ad2'] ?? null" />

                                @if (!empty($sectionCategories[13]))
                                    @include('components.slider-one-news-5', [
                                        'cat_id' => $sectionCategories[13]['catid'],
                                        'cat_name' => $sectionCategories[13]['name'],
                                        'cat_site_url' => $sectionCategories[13]['site_url'],
                                    ])
                                @endif

                            </main>
                        </div>
                    </div>
                    <div class="cm-col-lg-4 cm-col-12 sticky_portion px-1">
                        <aside id="secondary" class="sidebar-widget-area">

                            {{-- Vertical-Small-2 Advertise --}}
                            <x-vertical-sm-ad :ad="$data['homeAds']['home_sidebar_vertical_ad2'] ?? null" />

                            @foreach ($sidebarCategoriesList as $index => $sidebarCategory)
                                @include('components.sidebar-widget-3news', [
                                    'cat_id' => $sidebarCategory['catid'],
                                    'cat_name' => $sidebarCategory['name'],
                                    'cat_site_url' => $sidebarCategory['site_url'],
                                ])

                                @if ($index === 2)
                                    {{-- Show vote after the second sidebar (1-based index) --}}
                                    <div id="categories-2" class="widget widget_categories">
                                        <div class="news-tab">
                                            @if(!$showVoteInTopNews)
                                                @include('components.vote')
                                            @else
                                                @include('components.podcast')
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            {{-- Vertical-Small-2 Advertise --}}
                            <x-vertical-sm-ad :ad="$data['homeAds']['home_sidebar_vertical_ad3'] ?? null" />

                    </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="news-panel">
        <div class="cm-container">
            @if (!empty($sectionCategories[14]))
                @include('components.slider-two-news-5', [
                    'cat_id' => $sectionCategories[14]['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $sectionCategories[14]['site_url'],
                    'category_name' => $sectionCategories[14]['name'],
                ])
            @endif
        </div>
    </div>

    <div class="bottom-news-area news-area">
        <div class="cm-container">

            {{-- Horizontal-5 Advertise --}}
            <x-horizontal-ad :ad="$data['homeAds']['home_bottom_ad'] ?? null" />

            @php $loopCounter = 0; @endphp

            {{-- Loop for dynamic sections starting from index 16 --}}
            @foreach ($sectionCategories as $index => $section)
                @if ($index >= 15 && !empty($section) && isset($section['site_url'], $section['name'], $section['catid']))
                    @php $loopCounter++; @endphp

                    @php
                        $layoutType = $index % 4;
                    @endphp

                    {{-- Only show heading if the component does NOT have its own header --}}
                    {{-- @if (!in_array($layoutType, [0, 2])) --}}
                        {{-- skip news-nine-style (used in case 0 & 2) --}}
                        {{-- <div class="news-tabs nwstb">
                            <a class="newstab_title me-3" href="{{ $section['site_url'] }}">
                                <h2>{{ $section['name'] }}</h2>
                            </a>
                            <a href="{{ $section['site_url'] }}">अधिक<i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    @endif --}}

                    @switch($layoutType)
                        {{-- Rotate layouts every 4 sections --}}
                        @case(0)
                            @include('components.news-nine-style', [
                                'cat_id' => $section['catid'],
                                'cat_name' => $section['name'],
                                'cat_site_url' => $section['site_url'],
                            ])
                        @break

                        @case(1)
                            @include('components.slider-two-news-5', [
                                'cat_id' => $section['catid'],
                                'leftTitle' => 'ताजा खबर',
                                'middleTitle' => 'शीर्ष समाचार',
                                'rightTitle' => 'वीडियो',
                                'site_url' => $section['site_url'],
                                'category_name' => $section['name'],
                            ])
                        @break

                        @case(2)
                            @include('components.news-nine-style', [
                                'cat_id' => $section['catid'],
                                'cat_name' => $section['name'],
                                'cat_site_url' => $section['site_url'],
                            ])
                        @break

                        @case(3)
                            @include('components.slider-two-news-5', [
                                'cat_id' => $section['catid'],
                                'leftTitle' => 'ताजा खबर',
                                'middleTitle' => 'शीर्ष समाचार',
                                'rightTitle' => 'वीडियो',
                                'site_url' => $section['site_url'],
                                'category_name' => $section['name'],
                            ])
                        @break
                    @endswitch

                    {{-- 💡 Insert ad after every 2 sections --}}
                    @if ($loopCounter % 3 === 0)
                        <x-horizontal-ad :ad="$data['homeAds']['home_bottom_ad'] ?? null" />
                    @endif
                @endif
            @endforeach

        </div>
    </div>
    <!-- video js -->
<script src="{{ asset('asset/js/rashifal.js') }}" defer></script>
@endsection