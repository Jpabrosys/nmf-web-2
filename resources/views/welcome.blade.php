@extends('layouts.app')

{{-- Optimization: Preload Banner Image for LCP --}}
@section('head')
    @if(!empty($data['banners']['bannerimgurl']))
        <link rel="preload" as="image" href="{{ $data['banners']['bannerimgurl'] }}">
    @endif
@endsection

@section('content')

{{-- Unpack Data Variables for easier use --}}
@php
    $sectionCategories = $data['sectionCategories'];
    $sidebarCategoriesList = $data['sidebarCategories'];
    $bannerimgurl = $data['banners']['bannerimgurl'];
    $bannerlinkurl = $data['banners']['bannerlinkurl'];
    $bannermobileimgurl = $data['banners']['bannermobileimgurl'];
    $bannermobilelinkurl = $data['banners']['bannermobilelinkurl'];
    $flags = $data['flags'];
    $rajyaSection = $data['rajyaSection'];
    $bidhanSabhaSection = $data['bidhanSabhaSection'];
@endphp

{{-- Breaking News Section --}}
@if(isset($data['breakingNews']))
<div class="brk-m">
    <div class="cm-container">
        <div class="breaking-news">
            <div class="brk-news-wrap">
                <div class="brk-l">
                    <h4>
                        <div class="breaking-bars"><span></span><span></span><span></span><span></span></div>
                        Breaking News
                    </h4>
                </div>
                <div class="brk-r">
                    @php
                        $todayEng = str_replace(' ', '-', date('jS F Y'));
                    @endphp
                    <a class="brk-link"
                       href="{{ config('global.base_url').('breakingnews/latest-breaking-news-in-hindi-nmfnews-') }}{{ $todayEng }}">
                        {{ $data['breakingNews']->name }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Trending Tags Slider --}}
@if (!empty($data['uniqueTags']) && count(array_filter($data['uniqueTags'])))
<div class="swiper-tags-container">
    {{-- Accessibility: Added aria-label --}}
    <div class="swiper swiper-tags-main" aria-label="Trending Topics">
        <div class="gradient-left"></div>
        <div class="gradient-right"></div>
        <div class="swiper-wrapper swiper-tags-wrapper">
            @php $baseUrl = config('global.base_url'); @endphp
            @foreach ($data['uniqueTags'] as $tag)
                @if (trim($tag) !== '')
                    <a href="{{ rtrim($baseUrl, '/') }}/search?search={{ urlencode($tag) }}"
                       class="swiper-slide swiper-tag">{{ $tag }}</a>
                @endif
            @endforeach
        </div>
        {{-- Accessibility: Added role and aria-label --}}
        <div class="swiper-tags-button-prev" role="button" aria-label="Previous Tag"><i class="fas fa-chevron-left"></i></div>
        <div class="swiper-tags-button-next" role="button" aria-label="Next Tag"><i class="fas fa-chevron-right"></i></div>
    </div>
</div>
@endif

{{-- Conditional Election Sections --}}
@if ($flags['showExitpoll'] == 1)
    {{-- Show Exit Poll when Live is off --}}
    @include('components.election-exit-poll')
    @if ($flags['showBigEvent'])
        <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />
    @endif
@elseif ($flags['showLive'] == 1)
    {{-- Live has highest priority --}}
    @include('components.election-live-section')
    @if ($flags['showBigEvent'])
        <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />
    @endif
@endif

{{-- Big Event Section --}}
@if ($flags['showBigEvent'] && !empty($data['bigEvent']))
    @include('components.home.big-event', ['bigEvent' => $data['bigEvent']])
@endif

{{-- Horizontal Ad 1 --}}
<x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />

{{-- Banner Section --}}
@if ($flags['showBannerAboveTopNews'])
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
        'showVoteInTopNews' => $flags['showVoteInTopNews']
    ])
</section>

{{-- Banner Section (If not shown above) --}}
@if (!$flags['showBannerAboveTopNews'])
    @include('components.home.banner-section', [
        'bannerimgurl' => $bannerimgurl,
        'bannerlinkurl' => $bannerlinkurl,
        'bannermobileimgurl' => $bannermobileimgurl,
        'bannermobilelinkurl' => $bannermobilelinkurl,
    ])
@endif

{{-- App Download Modal --}}
<div id="appDownloadModal">
    <div class="app-download-modal">
        {{-- CLS Fix: Added width/height and style --}}
        <img class="modal-img" 
             src="{{ config('global.base_url_asset') }}asset/images/modal.webp" 
             alt="Download App Image"
             width="300" height="300"
             style="width: 100%; height: auto; max-width: 300px;">
        
        {{-- Accessibility: Added aria-label --}}
        <button class="modal-close-button" onclick="closeModal()" aria-label="Close Modal">×</button>
        <h2>Download Our App</h2>
        <p>Get the best experience by downloading our mobile app!</p>
        <div class="app_btn_wrap justify-content-center">
            <a href="https://www.newsnmf.com/nmfapps/" class="playstore-button" aria-label="Download on Playstore">
                <span class="texts">
                    <span class="text-2">Download the App</span>
                </span>
            </a>
        </div>
    </div>
</div>

{{-- Section 1 --}}
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
        <x-horizontal-ad :ad="$data['homeAds']['home_below_news_section_ad'] ?? null" />
    </div>
</div>

{{-- Web Stories --}}
<div class="web-stories-section">
    @include('components.webstory', ['webStories' => $data['webStories']])
</div>

{{-- Section 2 --}}
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

{{-- Reels Section --}}
<div class="news-panel reels">
    @include('components.reels-section')
</div>

{{-- Section 3 (Custom Block) --}}
<section class="custom_block">
    @if (!empty($sectionCategories[3]))
        @include('components.news-nine-style', [
            'cat_id' => $sectionCategories[3]['catid'],
            'cat_name' => $sectionCategories[3]['name'],
            'cat_site_url' => $sectionCategories[3]['site_url'],
        ])
    @endif
</section>

{{-- Video Section --}}
<section class="video-section">
    @include('components.video-gallery-allcat')
</section>

<x-horizontal-ad :ad="$data['homeAds']['home_below_video_section_ad'] ?? null" />

{{-- Section 4 --}}
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

        {{-- Rashifal Section --}}
        @if (collect($sectionCategories)->contains('name', 'धर्म ज्ञान') && !empty($data['rashifal']))
        <div class="rasifal-section">
            <div class="cm-container">
                <div class="rashifal-section">
                    <div class="rashifal-container" role="region" aria-label="राशिफल दैनिक">
                        <div class="rashifal-box">
                            <div class="rotating-bg" aria-hidden="true"></div>
                            <div class="rashifal-wrapper">
                                <button class="nav-btn prev" aria-label="पिछला राशिफल"><i class="fas fa-chevron-left"></i></button>
                                <div class="rashifal-slider" tabindex="0" role="listbox" aria-live="polite" aria-label="राशि चिन्ह">
                                    <div class="rashifal-item spacer" aria-hidden="true"></div>
                                    @foreach($data['rashifal'] as $index => $r)
                                        @php
                                            $rashiImg = config('global.base_url_image') . $r->full_path . '/' . $r->file_name;
                                        @endphp
                                        <div class="rashifal-item" role="option" aria-selected="{{ $index == 0 ? 'true':'false' }}">
                                            {{-- CLS Fix: Added width/height --}}
                                            <img src="{{ $rashiImg }}"
                                                 alt="{{ $r->name }}"
                                                 width="100" height="100"
                                                 data-sign="{{ strtolower($r->name) }}"
                                                 data-title="{{ $r->name }}"
                                                 data-description="{{ $r->description }}"
                                                 class="{{ $index == 0 ? 'active':'' }}"
                                                 loading="lazy" tabindex="0" />
                                        </div>
                                    @endforeach
                                    <div class="rashifal-item spacer" aria-hidden="true"></div>
                                </div>
                                <button class="nav-btn next" aria-label="अगला राशिफल"><i class="fas fa-chevron-right"></i></button>
                            </div>
                            <h2 id="rashifal-title">आपके तारे - दैनिक: {{ $data['rashifal'][0]->name ?? '' }}</h2>
                            <p id="rashifal-text">{{ $data['rashifal'][0]->description ?? '' }}</p>
                        </div>
                        {{-- Ad Sidebar --}}
                        <div class="adBgSidebar">
                            <div class="adtxt">Advertisement</div>
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-3986924419662120"
                                 data-ad-slot="6911924096"
                                 data-ad-format="auto"
                                 data-full-width-responsive="true"></ins>
                            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Section 5 (Custom Block) --}}
<section class="custom_block">
    @if (!empty($sectionCategories[5]))
        @include('components.news-nine-style', [
            'cat_id' => $sectionCategories[5]['catid'],
            'cat_name' => $sectionCategories[5]['name'],
            'cat_site_url' => $sectionCategories[5]['site_url'],
        ])
    @endif
</section>

{{-- States / Vidhan Sabha Tabs --}}
<div class="div_row mb-3">
    <div class="cm-container">
        <div class="news_tab_row">
            <div class="_devider">
                <div class="left_content news_tabs">
                    @if (!empty($rajyaSection))
                        @include('components.all-states-tab', [
                            'cat_id' => $rajyaSection['catid'],
                            'cat_name' => $rajyaSection['name'],
                            'cat_site_url' => $rajyaSection['site_url'],
                        ])
                    @endif

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

{{-- Section 6 --}}
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

{{-- Section 7 --}}
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

{{-- Section 8 --}}
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

{{-- Middle News Area with Sidebar --}}
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

                            <x-horizontal-sm-ad :ad="$data['homeAds']['home_middle_horz_sm_ad1'] ?? null" />

                            @if (!empty($sectionCategories[10]))
                                @include('components.slider-one-news-5', [
                                    'cat_id' => $sectionCategories[10]['catid'],
                                    'cat_name' => $sectionCategories[10]['name'],
                                    'cat_site_url' => $sectionCategories[10]['site_url'],
                                ])
                            @endif

                            {{-- Photo Slider (Section 11) --}}
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

                {{-- Sidebar Area --}}
                <div class="cm-col-lg-4 cm-col-12 sticky_portion px-1">
                    <aside id="secondary" class="sidebar-widget-area">
                        <x-vertical-sm-ad :ad="$data['homeAds']['home_sidebar_vertical_ad2'] ?? null" />

                        @foreach ($sidebarCategoriesList as $index => $sidebarCategory)
                            @include('components.sidebar-widget-3news', [
                                'cat_id' => $sidebarCategory['catid'],
                                'cat_name' => $sidebarCategory['name'],
                                'cat_site_url' => $sidebarCategory['site_url'],
                            ])
                            {{-- Show vote after the 2nd sidebar item (Index 2 in 1-based index) --}}
                            @if ($index === 2)
                                <div id="categories-2" class="widget widget_categories">
                                    <div class="news-tab">
                                        @if(!$flags['showVoteInTopNews'])
                                            @include('components.vote')
                                        @else
                                            @include('components.podcast')
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <x-vertical-sm-ad :ad="$data['homeAds']['home_sidebar_vertical_ad3'] ?? null" />
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section 14 --}}
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

{{-- Bottom Dynamic Sections Loop (Starting from 15) --}}
<div class="bottom-news-area news-area">
    <div class="cm-container">
        <x-horizontal-ad :ad="$data['homeAds']['home_bottom_ad'] ?? null" />

        @php $loopCounter = 0; @endphp

        @foreach ($sectionCategories as $index => $section)
            @if ($index >= 15 && !empty($section) && isset($section['site_url'], $section['name'], $section['catid']))
                @php 
                    $loopCounter++;
                    $layoutType = $index % 4;
                @endphp

                @switch($layoutType)
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

                {{-- Insert Ad after every 3 sections in this loop --}}
                @if ($loopCounter % 3 === 0)
                    <x-horizontal-ad :ad="$data['homeAds']['home_bottom_ad'] ?? null" />
                @endif
            @endif
        @endforeach

    </div>
</div>

{{-- Scripts with Safety Checks --}}
<script>
    // Ensure jQuery/Swiper are loaded before running dependent code
    document.addEventListener("DOMContentLoaded", function() {
        var waitForJQuery = setInterval(function() {
            if (typeof $ !== 'undefined') {
                clearInterval(waitForJQuery);
                // Trigger any jQuery dependent code here if necessary
            }
        }, 100);
    });
</script>
<script src="{{ asset('asset/js/rashifal.js') }}" defer></script>
@endsection