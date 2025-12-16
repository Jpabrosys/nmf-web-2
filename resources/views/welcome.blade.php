@extends('layouts.app')

{{-- Optimization: Preload Banner Image for LCP --}}
@section('head')
    @if(!empty($data['banners']['bannerimgurl']))
        <link rel="preload" as="image" href="{{ $data['banners']['bannerimgurl'] }}">
    @endif
    <style>
        /* Minimal styling for the loading state */
        .lazy-load-skeleton {
            min-height: 300px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s ease-in-out infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
            color: #999;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .lazy-load-container {
            min-height: 250px;
        }
        
        .lazy-load-container.loaded {
            min-height: auto;
        }
    </style>
@endsection

@section('content')

{{-- Unpack Data Variables Safely --}}
@php
    $sectionCategories = $data['sectionCategories'] ?? [];
    $sidebarCategoriesList = $data['sidebarCategories'] ?? [];
    $bannerimgurl = $data['banners']['bannerimgurl'] ?? null;
    $bannerlinkurl = $data['banners']['bannerlinkurl'] ?? null;
    $bannermobileimgurl = $data['banners']['bannermobileimgurl'] ?? null;
    $bannermobilelinkurl = $data['banners']['bannermobilelinkurl'] ?? null;
    $flags = $data['flags'] ?? [];
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
                    @php $todayEng = str_replace(' ', '-', date('jS F Y')); @endphp
                    <a class="brk-link" href="{{ config('global.base_url').('breakingnews/latest-breaking-news-in-hindi-nmfnews-') }}{{ $todayEng }}">
                        {{ $data['breakingNews']->name }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Trending Tags --}}
@if (!empty($data['uniqueTags']) && count(array_filter($data['uniqueTags'])))
<div class="swiper-tags-container">
    <div class="swiper swiper-tags-main" aria-label="Trending Topics">
        <div class="gradient-left"></div>
        <div class="gradient-right"></div>
        <div class="swiper-wrapper swiper-tags-wrapper">
            @php $baseUrl = config('global.base_url'); @endphp
            @foreach ($data['uniqueTags'] as $tag)
                @if (trim($tag) !== '')
                    <a href="{{ rtrim($baseUrl, '/') }}/search?search={{ urlencode($tag) }}" class="swiper-slide swiper-tag">{{ $tag }}</a>
                @endif
            @endforeach
        </div>
        <div class="swiper-tags-button-prev" role="button"><i class="fas fa-chevron-left"></i></div>
        <div class="swiper-tags-button-next" role="button"><i class="fas fa-chevron-right"></i></div>
    </div>
</div>
@endif

{{-- Election / Live Sections --}}
@if (($flags['showExitpoll'] ?? 0) == 1)
    @include('components.election-exit-poll')
    @if ($flags['showBigEvent'] ?? false) <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" /> @endif
@elseif (($flags['showLive'] ?? 0) == 1)
    @include('components.election-live-section')
    @if ($flags['showBigEvent'] ?? false) <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" /> @endif
@endif

{{-- Big Event --}}
@if (($flags['showBigEvent'] ?? false) && !empty($data['bigEvent']))
    @include('components.home.big-event', ['bigEvent' => $data['bigEvent']])
@endif

<x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />

{{-- Banner --}}
@if ($flags['showBannerAboveTopNews'] ?? false)
    @include('components.home.banner-section', ['bannerimgurl' => $bannerimgurl, 'bannerlinkurl' => $bannerlinkurl, 'bannermobileimgurl' => $bannermobileimgurl, 'bannermobilelinkurl' => $bannermobilelinkurl])
@endif

{{-- Top News --}}
<section class="top--news">
    @include('components.home.top-news-section', ['showVoteInTopNews' => $flags['showVoteInTopNews'] ?? false])
</section>

@if (!($flags['showBannerAboveTopNews'] ?? false))
    @include('components.home.banner-section', ['bannerimgurl' => $bannerimgurl, 'bannerlinkurl' => $bannerlinkurl, 'bannermobileimgurl' => $bannermobileimgurl, 'bannermobilelinkurl' => $bannermobilelinkurl])
@endif

{{-- App Modal --}}
<div id="appDownloadModal">
    <div class="app-download-modal">
        <img class="modal-img" src="{{ config('global.base_url_asset') }}asset/images/modal.webp" alt="Download App" width="300" height="300" style="width: 100%; height: auto; max-width: 300px;">
        <button class="modal-close-button" onclick="closeModal()">×</button>
        <h2>Download Our App</h2>
        <p>Get the best experience by downloading our mobile app!</p>
        <div class="app_btn_wrap justify-content-center">
            <a href="https://www.newsnmf.com/nmfapps/" class="playstore-button"><span class="texts"><span class="text-2">Download the App</span></span></a>
        </div>
    </div>
</div>

{{-- Section 1 --}}
<div class="news-panel">
    <div class="cm-container">
        @if (!empty($sectionCategories[1]))
            @include('components.slider-two-news-5', ['cat_id' => $sectionCategories[1]['catid'], 'leftTitle' => 'ताजा खबर', 'middleTitle' => 'शीर्ष समाचार', 'rightTitle' => 'वीडियो', 'site_url' => $sectionCategories[1]['site_url'], 'category_name' => $sectionCategories[1]['name']])
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
            @include('components.slider-two-news-5', ['cat_id' => $sectionCategories[2]['catid'], 'leftTitle' => 'ताजा खबर', 'middleTitle' => 'शीर्ष समाचार', 'rightTitle' => 'वीडियो', 'site_url' => $sectionCategories[2]['site_url'], 'category_name' => $sectionCategories[2]['name']])
        @endif
    </div>
</div>

{{-- SAFETY DIVS (To close any unclosed tags from above) --}}
</div></div></div></div>

{{-- 
    =======================================================
    START LAZY LOAD SECTIONS 
    =======================================================
--}}

{{-- Reels --}}
<div class="lazy-load-container news-panel reels" data-load-url="{{ route('api.lazy-load-section', ['section_id' => 'reels']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading Reels...</span></div>
</div>

{{-- Section 3 --}}
@if (!empty($sectionCategories[3]))
<div class="lazy-load-container custom_block" data-load-url="{{ route('api.lazy-load-section', ['section_id' => '3']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading {{ $sectionCategories[3]['name'] }}...</span></div>
</div>
@endif

{{-- Video --}}
<div class="lazy-load-container video-section" data-load-url="{{ route('api.lazy-load-section', ['section_id' => 'video']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading Videos...</span></div>
</div>

<x-horizontal-ad :ad="$data['homeAds']['home_below_video_section_ad'] ?? null" />

{{-- Section 4 --}}
@if (!empty($sectionCategories[4]))
<div class="lazy-load-container news-panel" data-load-url="{{ route('api.lazy-load-section', ['section_id' => '4']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading {{ $sectionCategories[4]['name'] }}...</span></div>
</div>
@endif

{{-- Section 5 --}}
@if (!empty($sectionCategories[5]))
<div class="lazy-load-container custom_block" data-load-url="{{ route('api.lazy-load-section', ['section_id' => '5']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading {{ $sectionCategories[5]['name'] }}...</span></div>
</div>
@endif

{{-- State Tabs (FIXED: Using direct array check to avoid 500 Error) --}}
@if (!empty($data['rajyaSection']) || !empty($data['bidhanSabhaSection']))
<div class="lazy-load-container div_row mb-3" data-load-url="{{ route('api.lazy-load-section', ['section_id' => 'state-tabs']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading State News...</span></div>
</div>
@endif

{{-- Section 6 --}}
@if (!empty($sectionCategories[6]))
<div class="lazy-load-container news-panel" data-load-url="{{ route('api.lazy-load-section', ['section_id' => '6']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading {{ $sectionCategories[6]['name'] }}...</span></div>
</div>
@endif

{{-- Section 7 --}}
@if (!empty($sectionCategories[7]))
<div class="lazy-load-container custom_block" data-load-url="{{ route('api.lazy-load-section', ['section_id' => '7']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading {{ $sectionCategories[7]['name'] }}...</span></div>
</div>
@endif

{{-- Section 8 --}}
@if (!empty($sectionCategories[8]))
<div class="lazy-load-container news-panel" data-load-url="{{ route('api.lazy-load-section', ['section_id' => '8']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading {{ $sectionCategories[8]['name'] }}...</span></div>
</div>
@endif

{{-- Middle News --}}
<div class="lazy-load-container middle-news-area news-area" data-load-url="{{ route('api.lazy-load-section', ['section_id' => 'middle-news-area']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading More News...</span></div>
</div>

{{-- Section 14 --}}
@if (!empty($sectionCategories[14]))
<div class="lazy-load-container news-panel" data-load-url="{{ route('api.lazy-load-section', ['section_id' => '14']) }}">
    <div class="lazy-load-skeleton"><span class="loading-text">Loading {{ $sectionCategories[14]['name'] }}...</span></div>
</div>
@endif

{{-- Bottom Dynamic --}}
<div class="bottom-news-area news-area">
    <div class="cm-container">
        <x-horizontal-ad :ad="$data['homeAds']['home_bottom_ad'] ?? null" />
        <div class="lazy-load-container" data-load-url="{{ route('api.lazy-load-section', ['section_id' => 'bottom-dynamic']) }}">
            <div class="lazy-load-skeleton"><span class="loading-text">Loading More Sections...</span></div>
        </div>
    </div>
</div>

{{-- 
    =======================================================
    NUCLEAR DEBUGGING SCRIPT 
    =======================================================
--}}
<script>
(function() {
    console.warn("🚀 STEP 1: Lazy Load Script STARTED execution.");
    
    function debugLog(step, message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        if(type === 'error') console.error(`[${timestamp}] ❌ ${step}: ${message}`);
        else if(type === 'success') console.log(`[${timestamp}] ✅ ${step}: ${message}`);
        else console.log(`[${timestamp}] ℹ️ ${step}: ${message}`);
    }

    function init() {
        debugLog("STEP 2", "DOM Content Loaded.");
        
        // FIND CONTAINERS
        const containers = document.querySelectorAll('.lazy-load-container');
        if (containers.length === 0) {
            debugLog("STEP 3", "CRITICAL ERROR: No lazy-load containers found!", 'error');
            return;
        }
        debugLog("STEP 3", `Found ${containers.length} containers.`, 'success');

        // OBSERVER
        if ('IntersectionObserver' in window) {
            debugLog("STEP 4", "Starting Observer...");
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const container = entry.target;
                        const url = container.getAttribute('data-load-url');
                        debugLog("STEP 5", `Intersection detected. Loading: ${url}`);
                        loadContent(container, url);
                        obs.unobserve(container);
                    }
                });
            }, { rootMargin: '300px', threshold: 0.01 });

            containers.forEach(c => observer.observe(c));
        } else {
            // FALLBACK
            debugLog("STEP 4", "Observer not supported. Loading immediately.");
            containers.forEach(c => loadContent(c, c.getAttribute('data-load-url')));
        }
    }

    async function loadContent(container, url) {
        debugLog("STEP 6", `Fetching: ${url}`);
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            debugLog("STEP 7", `Response Status: ${response.status}`);
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const html = await response.text();
            debugLog("STEP 8", `Data received (${html.length} chars). Injecting...`);
            
            if (html.length < 5) debugLog("WARNING", "Response looks empty!", 'error');

            container.innerHTML = html;
            container.classList.add('loaded');
            debugLog("STEP 9", "Content injected.", 'success');
            
        } catch (error) {
            debugLog("ERROR", `Fetch Failed: ${error.message}`, 'error');
            container.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        }
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
</script>

<script src="{{ asset('asset/js/rashifal.js') }}" defer></script>
@endsection