@extends('layouts.app')
@section('head')
    <link rel="amphtml" href="{{ url()->current() . '/amp' }}">
@endsection
{{-- Video.js CSS --}}
<link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
<link href="https://unpkg.com/videojs-theme-city@latest/dist/videojs-theme-city.css" rel="stylesheet" />

@section('content')
    <style>
        .breadcrumb {
            background: rgba(0, 0, 0, .03);
            margin-top: 30px;
            padding: 7px 20px;
            position: relative;
        }

        .section-title span {
            line-height: 36px !important;
        }

        .vjs-theme-city .vjs-control-bar {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .vjs-button.skip-btn {
            font-size: 16px;
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            cursor: pointer;
            margin: 0 5px;
            padding: 6px 12px;
        }
    </style>

    <div class="cm-container">
        <div class="inner-page-wrapper">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <div class="cm_archive_page">

                        {{-- Breadcrumb --}}
                        <div class="breadcrumb default-breadcrumb" style="display: block;">
                            <nav aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs" itemprop="breadcrumb">
                                <ul class="trail-items" itemscope itemtype="http://schema.org/BreadcrumbList">
                                    <meta name="numberOfItems" content="3">
                                    <meta name="itemListOrder" content="Ascending">
                                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-begin">
                                        <a href="{{ url('/') }}" itemprop="item"><span itemprop="name">Home</span></a>
                                        <meta itemprop="position" content="1">
                                    </li>
                                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-begin">
                                        <a href="{{ url('/videos') }}" itemprop="item"><span
                                                itemprop="name">Video</span></a>
                                        <meta itemprop="position" content="2">
                                    </li>
                                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"
                                        class="trail-item">
                                        <a href="{{ url('/videos/' . ($video->category->site_url ?? '')) }}"
                                            itemprop="item">
                                            <span itemprop="name">{{ $video->category->name }}</span>
                                        </a>
                                        <meta itemprop="position" content="3">
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- Horizontal-1 Ad --}}
                        <x-horizontal-ad :ad="$detailsAds['detail_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">

                                    {{-- Left Column --}}
                                    <div class="col_left">
                                        <div class="main_article_wrap">
                                            <div class="main_article">
                                                <h1 class="rt_main">{{ $video->title }}</h1>
                                                <!-- <p class="rt_sub">
                                                        {!! $video->description !!}
                                                    </p> -->
                                                {{-- Metadata --}}
                                                <div class="artcle_tab">
                                                    <div class="at_left">
                                                        <div class="editedby">
                                                            Created By:
                                                            <a
                                                                href="{{ url('/author/' . str_replace(' ', '_', $video->author->url_name ?? '-')) }}">
                                                                {{ $video->author->name ?? 'NMF News' }}
                                                            </a>
                                                        </div>
                                                        <div class="category_tag">
                                                            <i class="fa-solid fa-tag"></i>
                                                            <a
                                                                href="{{ url('/videos/' . $video->category->site_url ?? '') }}">
                                                                {{ $video->category->name }}
                                                            </a>
                                                        </div>
                                                        <div class="publish_wrap">
                                                            <div class="publish_dt">
                                                                <i class="fa-regular fa-calendar-days"></i>
                                                                <span>{{ $video->created_at->format('d M, Y') }}</span>
                                                            </div>

                                                            @if ($video->updated_at != $video->created_at)
                                                                <div class="publish_dt">
                                                                    (<i class="fa-regular fa-calendar-days"></i>
                                                                    <span>Updated:
                                                                        {{ $video->updated_at->format('d M, Y') }}</span>
                                                                </div>
                                                                <div class="publish_tm">
                                                                    <i class="fa-regular fa-clock"></i>
                                                                    <span>{{ $video->updated_at->format('h:i A') }})</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Share Buttons --}}
                                                    <div class="at_right">
                                                        @php
                                                            $videoUrl =
                                                                'https://www.newsnmf.com/video/' .
                                                                ($video->category->site_url ?? '-') .
                                                                '/' .
                                                                $video->site_url;
                                                        @endphp
                                                        <div class="c-row">
                                                            <div class="shr_dropdown">
                                                                <button class="shr-button">
                                                                    <svg viewBox="0 0 512 512"
                                                                        xmlns="http://www.w3.org/2000/svg" class="icon">
                                                                        <path
                                                                            d="M307 34.8c-11.5 5.1-19 16.6-19 29.2v64H176C78.8 128 0 206.8 0 304C0 417.3 81.5 467.9 100.2 478.1c2.5 1.4 5.3 1.9 8.1 1.9c10.9 0 19.7-8.9 19.7-19.7c0-7.5-4.3-14.4-9.8-19.5C108.8 431.9 96 414.4 96 384c0-53 43-96 96-96h96v64c0 12.6 7.4 24.1 19 29.2s25 3 34.4-5.4l160-144c6.7-6.1 10.6-14.7 10.6-23.8s-3.8-17.7-10.6-23.8l-160-144c-9.4-8.5-22.9-10.6-34.4-5.4z">
                                                                        </path>
                                                                    </svg>
                                                                    Share
                                                                </button>
                                                                <div class="shr_content" id="shr_content">

                                                                    <ul class="social_lnk">
                                                                        <li><a href="http://www.facebook.com/sharer.php?u={{ $videoUrl }}"
                                                                                target="_blank"><i
                                                                                    class="fab fa-facebook"></i></a></li>
                                                                        <li><a href="https://twitter.com/intent/tweet?url={{ $videoUrl }}"
                                                                                target="_blank"><i
                                                                                    class="fa-brands fa-x-twitter"></i></a>
                                                                        </li>
                                                                        <li><a href="https://web.whatsapp.com/send?text={{ $videoUrl }}"
                                                                                target="_blank"><i
                                                                                    class="fa-brands fa-whatsapp"></i></a>
                                                                        </li>
                                                                        <li class="d-block d-md-none"><a
                                                                                href="https://api.whatsapp.com/send?text={{ $videoUrl }}"
                                                                                target="_blank"><i
                                                                                    class="fa-brands fa-whatsapp"></i></a>
                                                                        </li>
                                                                        <li><a href="http://www.linkedin.com/shareArticle?mini=true&url={{ $videoUrl }}"
                                                                                target="_blank"><i
                                                                                    class="fab fa-linkedin"></i></a></li>
                                                                    </ul>
                                                                </div>

                                                            </div>
                                                            {{-- <a href="#comments-section" class="comment-button" id="commentToggle"
                                                                title="Comment">
                                                                <svg stroke-linejoin="round" stroke-linecap="round"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    viewBox="0 0 24 24" height="30" width="41"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    class="w-8 hover:scale-125 duration-200 hover:stroke-blue-500"
                                                                    fill="none">
                                                                    <path fill="none" d="M0 0h24v24H0z" stroke="none">
                                                                    </path>
                                                                    <path d="M8 9h8"></path>
                                                                    <path d="M8 13h6"></path>
                                                                    <path
                                                                        d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z">
                                                                    </path>
                                                                </svg>

                                                            </a> --}}
                                                        </div>

                                                    </div>
                                                </div>
                                                {{-- Video / Thumbnail --}}
                                                <div class="at_img">
                                                    <figure>
                                                        @if ($video->video_path)
                                                            {{-- <video id="custom_video"
                                                                class="video-js vjs-theme-city vjs-big-play-centered"
                                                                controls preload="auto" width="100%" height="auto"
                                                                poster="{{ asset($video->thumbnail_path) }}"
                                                                data-setup='{"playbackRates": [0.75, 1, 1.25, 1.5, 2]}'>
                                                                <source src="{{ asset($video->video_path) }}"
                                                                    type="video/mp4">
                                                                Your browser does not support HTML5 video.
                                                            </video> --}}

                                                            <video controls autoplay muted
                                                                class="--video-detail respnsive_iframe">
                                                                <source
                                                                    src="{{ config('global.base_url_videos') . $video->video_path }}"
                                                                    type="video/mp4">
                                                                Your browser does not support the video tag.

                                                            </video>
                                                        @elseif ($video->thumbnail_path)
                                                            <img src="{{ asset($video->thumbnail_path) }}"
                                                                alt="{{ $video->title }}">
                                                        @endif
                                                    </figure>
                                                </div>



                                                {{-- Description --}}
                                                <div class="at_content">
                                                    {{-- {!! $video->description !!} --}}
                                                    <p class="rt_sub2 mt-1">
                                                        {!! $video->description !!}
                                                    </p>

                                                    {{-- Google Ad --}}
                                                    <section class="cm_related_post_container">
                                                        <div class="section_inner">

                                                            {{-- Horizontal-Small-1 Advertise --}}
                                                            <x-horizontal-sm-ad :ad="$detailsAds['detail_middle_horz_sm_ad1'] ?? null" />

                                                            {{-- Related Videos --}}
                                                            <div class="rel_artcle_wrap">
                                                                <ul class="rel_content">
                                                                    @foreach ($latests as $latest)
                                                                        <li>
                                                                            <article class="rel_article">
                                                                                <div class="rel_top">
                                                                                    <div class="playBtn-wrap3">
                                                                                        <span class="play-btn3"><i
                                                                                                class="fa-solid fa-play"></i></span>
                                                                                        <span class="v-duration3">08:06</span>
                                                                                    </div>
                                                                                    <a
                                                                                        href="{{ url('/video/' . ($latest->category->site_url ?? '-') . '/' . $latest->site_url) }}">
                                                                                        <img src="{{ config('global.base_url_videos') . $latest->thumbnail_path }}"
                                                                                            alt="{{ $latest->title }}">
                                                                                    </a>
                                                                                    <a href="{{ url('/videos/' . ($latest->category->site_url ?? '')) }}"
                                                                                        class="nws_article_strip">
                                                                                        {{ $latest->category->name }}
                                                                                    </a>
                                                                                </div>
                                                                                <div class="rel_bottom">
                                                                                    <a href="{{ url('/video/' . ($latest->category->site_url ?? '-') . '/' . $latest->site_url) }}"
                                                                                        class="rel_link">
                                                                                        {{ $latest->title }}
                                                                                    </a>
                                                                                </div>
                                                                            </article>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right Column --}}
                                    <div class="col_right">
                                        @include('components.latestStories')
                                        <x-vertical-sm-ad :ad="$detailsAds['detail_sidebar_vertical_ad1'] ?? null" />

                                        {{-- Optimized Side Widgets --}}
                                        @foreach ($sideWidgets as $widget)
                                            @include('components.side-widgets', [
                                                'categoryName' => $widget['categoryName'],
                                                'category' => $widget['category'],
                                                'blogs' => $widget['blogs'],
                                            ])
                                        @endforeach

                                        <x-vertical-sm-ad :ad="$detailsAds['detail_sidebar_vertical_ad2'] ?? null" />
                                    </div>
                                </div>

                                {{-- Bottom Ad --}}
                                <x-horizontal-ad :ad="$detailsAds['detail_bottom_ad'] ?? null" />
                            </div>
                        </section>

                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

{{-- Video.js JS --}}
<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
<!-- Google IMA SDK -->
<script src="https://imasdk.googleapis.com/js/sdkloader/ima3.js"></script>
<!-- Video.js Ads Plugin -->
<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-ads@6.9.0/dist/videojs-contrib-ads.min.js"></script>
<!-- Video.js IMA Plugin -->
<script src="https://cdn.jsdelivr.net/npm/videojs-ima@1.9.1/dist/videojs.ima.min.js"></script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const player = videojs('custom_video');
        const videoKey = 'video_time_{{ $video->id }}';

        // Resume saved time
        const savedTime = localStorage.getItem(videoKey);
        if (savedTime) {
            player.currentTime(savedTime);
        }

        // Save time periodically
        player.on('timeupdate', function() {
            localStorage.setItem(videoKey, player.currentTime());
        });

        // Initialize IMA plugin with test ad (or replace with your own)
        player.ima({
            //adTagUrl: 'https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/YOUR_NETWORK/YOUR_UNIT&cust_params=video_id%3D{{ $video->id }}&env=vp&gdfp_req=1&output=vast&unviewed_position_start=1&ad_rule=1&correlator=',
            adTagUrl: 'https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/124319096/external/single_ad_samples&ciu_szs=300x250&impl=s&gdfp_req=1&env=vp&output=vast&unviewed_position_start=1&ad_rule=1&cust_params=deployment%3Ddevsite%26sample_ct%3Dlinear&correlator=',
            debug: true
        });

        // Load and request ads
        player.ready(function() {
            player.ima.initializeAdDisplayContainer();
            player.ima.requestAds();
        });
    });
</script>
