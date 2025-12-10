@extends('layouts.app')
@section('head')
    <link rel="amphtml" href="{{ url()->current() . '/amp' }}">
@endsection
@section('content')
    <?php
    //$ff = config('global.blog_images_everywhere')($data['blog'] ?? null);
    $ff = cached_blog_image($data['blog']);
    $imageToUse = $ff;
    $customImageUrl = "{{config('global.base_url_asset')}}asset/images/NMF_BreakingNews.png";
    ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "NewsArticle",
            "headline": {!! json_encode(Str::limit($data['blog']->name, 110)) !!},
            "description": {!! json_encode(Str::limit(strip_tags($data['blog']->sort_description ?? $data['blog']->description), 200)) !!},

            "image": [
                @if (!empty($imageToUse ))
                "{{ $imageToUse  }}"
                @else
                "{{ $customImageUrl }}"
                @endif
            ],
            "author": {
                "@type": "Person",
                "name": {!! json_encode($data['author']->name ?? 'NMF News') !!}
            },
            "publisher": {
                "@type": "Organization",
                "name": "NMF News",
               "logo": {
                    "@type": "ImageObject",
                    "url": "{{config('global.base_url_frontend')}}frontend/images/logo.png"
                }
            },
            "datePublished": "{{ $data['blog']->created_at->toIso8601String() }}",
            "dateModified": "{{ $data['blog']->updated_at->toIso8601String() }}",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "{{ url()->current() }}"
            }
        }
    </script>

    @if ($data['youtubeVideoId'] || str_contains($data['blog']->link ?? '', '.mp4'))
        <script type="application/ld+json">
    {!! json_encode([
        "@context" => "https://schema.org",
        "@type" => "VideoObject",
        "name" => $data['blog']->name,
        "description" => Str::limit(strip_tags($data['blog']->sort_description ?? $data['blog']->description), 200),
        "thumbnailUrl" => cached_blog_image($data['blog']),
        "uploadDate" => \Carbon\Carbon::parse($data['blog']->created_at)->toIso8601String(),
        "contentUrl" => $data['blog']->link,
        "embedUrl" => $data['youtubeVideoId'] 
                        ? "https://www.youtube.com/embed/" . $data['youtubeVideoId'] 
                        : (str_contains($data['blog']->link, '.mp4') ? $data['blog']->link : null),
        "publisher" => [
            "@type" => "Organization",
            "name" => "NMF News",
            "logo" => [
                "@type" => "ImageObject",
                "url" => config('global.base_url_frontend') . "frontend/images/logo.png",
                "width" => 300,
                "height" => 60,
            ]
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif
    <style>
        .breadcrumb {
            background: rgba(0, 0, 0, .03);
            margin-top: 30px;
            padding: 7px 20px;
            position: relative;
        }

        .section-title span {
            line-height: 36px;
            !important;
        }
    </style>

    <div class="cm-container" style="transform: none;">
        <div class="inner-page-wrapper" style="transform: none;">
            <div id="primary" class="content-area" style="transform: none;">
                <main id="main" class="site-main" style="transform: none;">
                    <div class="cm_archive_page" style="transform: none;">
                        <div class="breadcrumb  default-breadcrumb" style="display: block;">
                            <nav role="navigation" aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs"
                                itemprop="breadcrumb">
                                <ul class="trail-items" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                                    <meta name="numberOfItems" content="3">
                                    <meta name="itemListOrder" content="Ascending">
                                    <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-begin"><a href="/" rel="home"
                                            itemprop="item"><span itemprop="name">Home</span></a>
                                        <meta itemprop="position" content="1">
                                    </li>
                                    <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                        class="trail-item"><a
                                            href="{{ asset('/') }}{{ isset($data['category']->site_url) ? $data['category']->site_url : '' }}"
                                            itemprop="item"><span itemprop="name">{{ $data['category']->name }}</span></a>
                                        <meta itemprop="position" content="2">
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        <script type="application/ld+json">
                            {
                                "@context": "https://schema.org",
                                "@type": "BreadcrumbList",
                                "itemListElement": [
                                    {
                                    "@type": "ListItem",
                                    "position": 1,
                                    "name": "Home",
                                    "item": "{{ url('/') }}"
                                    },
                                    {
                                    "@type": "ListItem",
                                    "position": 2,
                                    "name": "राज्य",
                                    "item": "{{ url('/states') }}"
                                    },
                                    {
                                    "@type": "ListItem",
                                    "position": 3,
                                    "name": {!! json_encode($data['blog']->name) !!},
                                    "item": "{{ url()->current() }}"
                                    }
                                ]
                            }
                        </script>

                        {{-- Horizontal-1 Advertise --}}
                        <x-horizontal-ad :ad="$data['detailsAds']['detail_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="main_article_wrap">
                                            <div class="main_article">
                                                <h1 class="rt_main">
                                                    {{-- @if ($data['blog']->status == 0)
                                                            <span class="draft_box">Unpublish</span>
                                                        @endif --}}

                                                    {{ $data['blog']->name }}
                                                </h1>

                                                <h2 class="rt_sub">{{ $data['blog']->sort_description }}</h2>

                                                <div class="artcle_tab">
                                                    <div class="at_left">
                                                        <div class="auth-box">
                                                           <img class="auth-img" width="40" height="40"
                                                            src="{{ $data['author']->image ? asset('file/' . $data['author']->image) : asset('asset/images/nmf-author.webp') }}"
                                                            alt="Author">

                                                            <div class="info-area">
                                                                <div class="editedby"> <a
                                                                        href="{{ asset('/author') }}/{{ str_replace(' ', '_', isset($data['author']->url_name) ? $data['author']->url_name : '-') }}">{{ isset($data['author']->name) ? $data['author']->name : 'NMF News' }}</a>
                                                                </div>
                                                                <div class="info-inner">
                                                                    <div class="category_tag">
                                                                <a
                                                                            href="/{{ isset($data['category']->site_url) ? $data['category']->site_url : '' }}">{{ $data['category']->name }}</a>
                                                                    </div>
                                                                    <div class="publish_wrap">
                                                                        <div class="publish_dt">
                                                                            <span>{{ $data['blog']->created_at->format('d M Y') }}</span>
                                                                        </div>

                                                                        @if ($data['blog']->updated_at)
                                                                            <div class="publish_dt"> (
                                                                                <span>Updated:
                                                                                    {{ $data['blog']->updated_at->format('d M Y') }}</span>
                                                                            </div>
                                                                            <div class="publish_tm">
                                                                                <span>{{ $data['blog']->updated_at->format('h:i A') }}
                                                                                    ) </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>

                                                    <div class="at_right">
                                                        @php
                                                            $catname = $data['category']->site_url ?? '';
                                                            $slug = $data['blog']->site_url;
                                                            $shareUrl = url("$catname/$slug");
                                                        @endphp
                                                        
                                                        <div class="c-row">  
                                                            <div class="shr_dropdown">
                                                                <button class="shr-button">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" width="24" height="24">
                                                                        <circle cx="18" cy="5" r="3"
                                                                            stroke="#000" stroke-width="2"
                                                                            fill="none" />
                                                                        <circle cx="6" cy="12" r="3"
                                                                            stroke="#000" stroke-width="2"
                                                                            fill="none" />
                                                                        <circle cx="18" cy="19" r="3"
                                                                            stroke="#000" stroke-width="2"
                                                                            fill="none" />
                                                                        <line x1="8.5" y1="13" x2="15.5"
                                                                            y2="6" stroke="#000" stroke-width="2"
                                                                            stroke-linecap="round" />
                                                                        <line x1="8.5" y1="11"
                                                                            x2="15.5" y2="18" stroke="#000"
                                                                            stroke-width="2" stroke-linecap="round" />
                                                                    </svg>


                                                                </button>
                                                                <div class="shr_content">

                                                                    <ul class="social_lnk">
                                                                        <li>
                                                                            <a href="http://www.facebook.com/sharer.php?u={{ $shareUrl }}"
                                                                                target="_blank">
                                                                                <i class="fab fa-facebook"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode($data['blog']->name) }}"
                                                                                target="_blank">
                                                                                <i class="fa-brands fa-x-twitter"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="https://web.whatsapp.com/send?text={{ $shareUrl }}"
                                                                                target="_blank">
                                                                                <i class="fa-brands fa-whatsapp"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class="d-block d-md-none">
                                                                            <a href="https://api.whatsapp.com/send?text={{ $shareUrl }}"
                                                                                target="_blank">
                                                                                <i class="fa-brands fa-whatsapp"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="http://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}"
                                                                                target="_blank">
                                                                                <i class="fab fa-linkedin"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <a href="#comments-section" class="commt-button"
                                                                id="commentToggle" title="Comment">
                                                                <svg viewBox="0 0 24 24" class="comment-icon"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M8 9h8" stroke="#000" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                    </path>
                                                                    <path d="M8 13h6" stroke="#000" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                    </path>
                                                                    <path
                                                                        d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z"
                                                                        stroke="#000" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        fill="none"></path>
                                                                </svg>
                                                                <span>Comment</span>
                                                            </a>


                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="at_img">
                                                    <figure class="relative">
                                                        @if ($data['youtubeVideoId'])
                                                            {{-- This is the new logic for YouTube --}}
                                                            <div class="respnsive_iframe">
                                                                <iframe loading="lazy"
                                                                    class="attachment-full size-full wp-post-image"
                                                                    src="https://www.youtube.com/embed/{{ $data['youtubeVideoId'] }}"
                                                                    frameborder="0"
                                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                    allowfullscreen>
                                                                </iframe>
                                                            </div>
                                                        @elseif (!empty($data['blog']->link) && str_contains($data['blog']->link, '.mp4'))
                                                            {{-- This is for other .mp4 videos --}}
                                                            <video controls="controls" autoplay muted id="video1"
                                                                class="--video respnsive_iframe"
                                                                style="padding-top:0px!important;">
                                                                <source src="{{ $data['blog']->link }}"
                                                                    type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                                                            </video>
                                                        @else
                                                            {{-- This is the fallback image --}}
                                                            @php
                                                                $ff = cached_blog_image($data['blog']);
                                                            @endphp
                                                            <img @if (!empty($ff)) src="{{ $ff }}" @endif
                                                                alt="{{ $data['blog']->name }}">
                                                        @endif

                                                        {{-- Image Credit --}}
                                                        @if (!empty($data['blog']->credits))
                                                            <div class="at_img_credit py-2 ps-3">
                                                                <span>{{ $data['blog']->credits }}</span>
                                                            </div>
                                                        @endif
                                                    </figure>
                                                </div>
                                                <div class="follow_us">
                                                    <div class="follow_us_bar"></div>
                                                    <div class="flw_wrap">
                                                        <h6>Follow Us: </h6>
                                                        <div class="follow_us_icon">
                                                            <div class="follow_us_socials">
                                                                <a href="https://www.facebook.com/NMFNewsNational/"
                                                                    target="_blank" title="Facebook"
                                                                    class="socials-item">
                                                                    <i class="fab fa-facebook-f facebook"></i>
                                                                </a>
                                                                <a href="https://x.com/NMFNewsOfficial" target="_blank"
                                                                    title="Twitter" class="socials-item">
                                                                    <i class="fa-brands fa-x-twitter"></i>
                                                                </a>
                                                                <a href="https://instagram.com/nmfnewsofficial"
                                                                    target="_blank" title="Instagram"
                                                                    class="socials-item">
                                                                    <i class="fab fa-instagram instagram"></i>
                                                                </a>
                                                                <a href="https://www.youtube.com/c/NMFNews/featured"
                                                                    target="_blank" title="YouTube" class="socials-item">
                                                                    <i class="fab fa-youtube youtube"></i>
                                                                </a>
                                                                <a href="https://whatsapp.com/channel/0029VajdZqv9xVJbRYtSFM3C"
                                                                    target="_blank" title="WhatsApp"
                                                                    class="socials-item">
                                                                    <i class="fab fa-whatsapp whatsapp"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="follow_us_bar"></div>
                                                </div>
                                                <div class="at_content">
                                                    @php
                                                        $description = $data['blog']->description ?? '';

                                                        // Split description by </p> but keep closing tag so structure stays intact
                                                        $paragraphs = preg_split(
                                                            '/(<\/p>)/i',
                                                            $description,
                                                            -1,
                                                            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY,
                                                        );

                                                        // Count blocks: all <p> tags count
                                                        $pCount = preg_match_all('/<p[^>]*>.*?<\/p>/is', $description);
                                                        $divCount = preg_match_all(
                                                            '/<div[^>]*>.*?<\/div>/is',
                                                            $description,
                                                        );
                                                        $blockCount = $pCount + $divCount;

                                                        // Decide where to inject
                                                        // $injectionIndex = $blockCount >= 3 ? 3 : ($blockCount == 2 ? 2 : ($blockCount == 1 ? 1 : 0));
                                                        $injectionIndex = $blockCount > 1 ? $blockCount - 1 : 0;
                                                        $injected = false;
                                                        $count = 0;

                                                        // Helper: detect if block is pseudo-heading (<p><strong>...</strong></p> only)
                                                        function isPseudoHeading($html)
                                                        {
                                                            return preg_match(
                                                                '/^<p[^>]*>\s*<strong>.*<\/strong>\s*<\/p>$/is',
                                                                trim($html),
                                                            );
                                                        }
                                                    @endphp

                                                    @if ($injectionIndex === 0)
                                                        @if (!empty($data['latests']) && $data['latests']->isNotEmpty())
                                                            @include('components.related-articles', [
                                                                'articles' => $data['latests'],
                                                            ])
                                                        @endif
                                                        @php $injected = true; @endphp
                                                    @endif

                                                    @foreach ($paragraphs as $block)
                                                        {!! $block !!}
                                                        @if (preg_match('/<\/p>/i', $block))
                                                            @php $count++; @endphp

                                                            @if (!$injected && $count === $injectionIndex)
                                                                {{-- If this block is a pseudo-heading, inject BEFORE it --}}
                                                                @if (isPseudoHeading($block))
                                                                    @if (!empty($data['latests']) && $data['latests']->isNotEmpty())
                                                                        @include(
                                                                            'components.related-articles',
                                                                            ['articles' => $data['latests']]
                                                                        )
                                                                    @endif
                                                                    @php $injected = true; @endphp
                                                                @else
                                                                    @if (!empty($data['latests']) && $data['latests']->isNotEmpty())
                                                                        @include(
                                                                            'components.related-articles',
                                                                            ['articles' => $data['latests']]
                                                                        )
                                                                    @endif
                                                                    @php $injected = true; @endphp
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                    @if (!$injected)
                                                        @if (!empty($data['latests']) && $data['latests']->isNotEmpty())
                                                            @include('components.related-articles', [
                                                                'articles' => $data['latests'],
                                                            ])
                                                        @endif
                                                    @endif


                                                    <div class="wp-btn">
                                                        <a href="https://whatsapp.com/channel/0029VajdZqv9xVJbRYtSFM3C"
                                                            class="button2">
                                                            व्हॉट्सऐप चैनल से जुड़ें
                                                            <svg viewBox="0 0 48 48" y="0px" x="0px"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z"
                                                                    fill="#fff"></path>
                                                                <path
                                                                    d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z"
                                                                    fill="#fff"></path>
                                                                <path
                                                                    d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z"
                                                                    fill="#cfd8dc"></path>
                                                                <path
                                                                    d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z"
                                                                    fill="#40c351"></path>
                                                                <path clip-rule="evenodd"
                                                                    d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z"
                                                                    fill-rule="evenodd" fill="#fff"></path>
                                                            </svg>
                                                        </a>
                                                    </div>

                                                    {{-- Tags --}}
                                                    @if (!empty($data['blog']->tags))
                                                        <section class="cm_related_post_container">
                                                            <div class="section_inner">
                                                                <div class="section-title">
                                                                    <h2>Tags</h2>
                                                                </div>
                                                                <div class="row tags_row ms-2">
                                                                    @foreach (explode(',', $data['blog']->tags) as $tag)
                                                                        @if (trim($tag) !== '')
                                                                            <a href="{{ url('/search?search=' . trim($tag)) }}"
                                                                                class="trendingtopic_container">
                                                                                {{ trim($tag) }}
                                                                            </a>
                                                                        @endif
                                                                    @endforeach
                                                                </div>

                                                                {{-- Horizontal-Small-1 Advertise --}}
                                                                <x-horizontal-sm-ad :ad="$data['detailsAds'][
                                                                    'detail_middle_horz_sm_ad1'
                                                                ] ?? null" />

                                                            </div>
                                                        </section>
                                                    @endif
                                                </div>
                                                <div id="comments-section">
                                                    @include('components.comment-section', [
                                                        'model' => $data['blog'],
                                                        'comments' => $data['comments'] ?? [],
                                                        'isLoggedIn' => $data['isLoggedIn'] ?? false,
                                                        'currentViewer' =>
                                                            $data['isLoggedIn'] ?? false
                                                                ? $data['currentViewer']
                                                                : null,
                                                    ])
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Sidebar --}}
                                        <div class="col_right">
                                            {{-- - 10 latest articles displayed - --}}
                                            @include('components.latestStories')

                                            {{-- Vertical-Small-1 Advertise --}}
                                            <x-vertical-sm-ad :ad="$data['detailsAds']['detail_sidebar_vertical_ad1'] ?? null" />

                                            @php
                                                $categories = [
                                                    ['name' => 'ट्रेंडिंग न्यूज़', 'limit' => 5],
                                                    ['name' => 'पॉडकास्ट', 'limit' => 1],
                                                    ['name' => 'टेक्नोलॉजी', 'limit' => 5],
                                                    ['name' => 'स्पेशल्स', 'limit' => 5],
                                                ];
                                            @endphp

                                            @foreach ($categories as $cat)
                                                @php
                                                    $category = App\Models\Category::where(
                                                        'name',
                                                        $cat['name'],
                                                    )->first();

                                                    $blogs = App\Models\Blog::where('status', 1)
                                                        ->where('categories_ids', $category->id ?? 0)
                                                        ->where('id', '!=', $data['blog']->id) //  EXCLUDE CURRENT BLOG
                                                        ->orderBy('updated_at', 'desc')
                                                        ->limit($cat['limit'])
                                                        ->get();
                                                @endphp

                                                @if ($blogs->isNotEmpty())
                                                    @include('components.side-widgets', [
                                                        'categoryName' => $cat['name'],
                                                        'category' => $category,
                                                        'blogs' => $blogs,
                                                    ])
                                                @endif
                                            @endforeach

                                            {{-- Vertical-Small-2 Advertise --}}
                                            <x-vertical-sm-ad :ad="$data['detailsAds']['detail_sidebar_vertical_ad2'] ?? null" />

                                        </div>
                                    </div>

                                    {{-- Horizontal-2 Advertise --}}
                                    <x-horizontal-ad :ad="$data['detailsAds']['detail_bottom_ad'] ?? null" />

                                </div>
                        </section>
                    </div>

                </main>
            </div>
        </div>
    </div>

    {{-- ---Web Hit Count Increment-- --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let isScrolling = false;
            let scrollTimer = null;
            let scrollStartTime = null;
            let hitCountSent = false;

            window.addEventListener('scroll', () => {
                if (!isScrolling) {
                    isScrolling = true;
                    scrollStartTime = Date.now();
                    scrollTimer = setInterval(() => {
                        const elapsed = (Date.now() - scrollStartTime) / 1000;
                        if (elapsed >= 10 && !hitCountSent) {
                            hitCountSent = true;
                            sendHitCount({{ $data['blog']->id }});
                            clearInterval(scrollTimer);
                        }
                    }, 1000);
                }
            });

            function sendHitCount(blogId) {
                fetch('/increase-webhitcount', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            blog_id: blogId
                        })
                    })
                    .then(res => res.json())
                    .then(data => console.log('Webhit count updated:', data))
                    .catch(err => console.error('Error updating hit count:', err));
            }
        });
    </script>
@endsection