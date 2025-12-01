@extends('layouts.app')
@section('content')

@section('content')
    @php
        // --- Logic for Schemas ---
        $blogImage = config('global.blog_images_everywhere')($blogs ?? null);
        $customImageUrl = config('global.base_url_asset') . 'asset/images/NMF_BreakingNews.png';
        $imageToUse = !empty($blogImage) ? $blogImage : $customImageUrl;

        // YouTube ID Extractor Logic
        $youtubeVideoId = null;
        if (!empty($blogs->link)) {
            $pattern =
                '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|u\/\w\/|watch\?.+&v=)?([\w\-]{11})(?:.+)?/';
            if (preg_match($pattern, $blogs->link, $matches)) {
                $youtubeVideoId = $matches[1];
            }
        }
    @endphp

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": {!! json_encode(Str::limit($blogs->name, 110)) !!},
        "description": {!! json_encode(Str::limit(strip_tags($blogs->sort_description ?? $blogs->description), 200)) !!},
        "image": [
            "{{ $imageToUse }}"
        ],
        "author": {
            "@type": "Person",
            "name": {!! json_encode($author->name ?? 'NMF News') !!}
        },
        "publisher": {
            "@type": "Organization",
            "name": "NMF News",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ config('global.base_url_frontend') }}frontend/images/logo.png"
            }
        },
        "datePublished": "{{ $blogs->created_at->toIso8601String() }}",
        "dateModified": "{{ $blogs->updated_at->toIso8601String() }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
        }
    }
    </script>

    @if ($youtubeVideoId || str_contains($blogs->link ?? '', '.mp4'))
        <script type="application/ld+json">
        {!! json_encode([
            "@context" => "https://schema.org",
            "@type" => "VideoObject",
            "name" => $blogs->name,
            "description" => Str::limit(strip_tags($blogs->sort_description ?? $blogs->description), 200),
            "thumbnailUrl" => $imageToUse,
            "uploadDate" => \Carbon\Carbon::parse($blogs->created_at)->toIso8601String(),
            "contentUrl" => $blogs->link,
            "embedUrl" => $youtubeVideoId 
                            ? "https://www.youtube.com/embed/" . $youtubeVideoId 
                            : (str_contains($blogs->link, '.mp4') ? $blogs->link : null),
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
    <link rel="stylesheet" href="{{ asset('asset/css/liveblogs.css') }}" type="text/css" media="all">
    <div class="cm-container" style="transform: none;">
        <div class="inner-page-wrapper" style="transform: none;">
            <div id="primary" class="content-area" style="transform: none;">
                <main id="main" class="site-main" style="transform: none;">
                    <div class="cm_archive_page" style="transform: none;">
                        {{-- Correct 3-level breadcrumb --}}
                        <div class="breadcrumb  default-breadcrumb" style="display: block;">
                            <nav role="navigation" aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs"
                                itemprop="breadcrumb">
                                <ul class="trail-items" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                                    <meta name="numberOfItems" content="3">
                                    <meta name="itemListOrder" content="Ascending">

                                    <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-begin">
                                        <a href="{{ url('/') }}" rel="home" itemprop="item">
                                            <span itemprop="name">Home</span>
                                        </a>
                                        <meta itemprop="position" content="1">
                                    </li>

                                    <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                        class="trail-item">
                                        <a href="{{ url($category->site_url) }}" itemprop="item">
                                            <span itemprop="name">{{ $category->name }}</span>
                                        </a>
                                        <meta itemprop="position" content="2">
                                    </li>

                                    {{-- The current page (article) is not a link --}}
                                    {{-- This is the FIXED code --}}
                                    <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-end">
                                        <span itemprop="item" itemscope itemtype="http://schema.org/WebPage">
                                            <meta itemprop="url" content="{{ url()->current() }}" />
                                            <span itemprop="name">{{ strip_tags($blogs->name) }}</span>
                                        </span>

                                        <meta itemprop="position" content="3">
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- Horizontal-1 Advertise --}}
                        <x-horizontal-ad :ad="$liveDetailAds['detail_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="main_article_wrap">
                                            <div class="main_article">
                                                <h1 class="rt_main"> {{ $blogs->name }}</h1>
                                                <h2 class="rt_sub">{{ $blogs->sort_description }}</h2>
                                                <div class="artcle_tab">
                                                    <div class="at_left flex-wrap-reverse flex-md-wrap ">
                                                        <div class="editedby">Created By: <a
                                                                href="{{ asset('/author/-') }}"> {{ $author->name }}</a>
                                                        </div>

                                                        <div class="category_tag"><i class="fa-solid fa-tag"></i><a
                                                                href="/news">{{ $category->name }}</a>
                                                        </div>
                                                        <div class="publish_wrap">
                                                            <div class="publish_dt">
                                                                <i class="fa-regular fa-calendar-days"></i>
                                                                <span>{{ \Carbon\Carbon::parse($blogs->created_at)->format('d M, Y') }}</span>
                                                            </div>
                                                            <div class="publish_tm">
                                                                <i class="fa-regular fa-clock"></i>
                                                                <span>{{ \Carbon\Carbon::parse($blogs->created_at)->format('h:i A') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $imageUrl = config('global.blog_images_everywhere')($blogs);
                                                @endphp
                                                @if ($imageUrl)
                                                    <div class="at_img mb-2">
                                                        <figure>
                                                            <img @if (!empty($imageUrl)) src="{{ $imageUrl }}" @endif
                                                                alt="{{ $blogs->name }}" loading="lazy"
                                                                onerror="this.style.display='none';"
                                                                style="width: 100%; height: 100%; object-fit: cover;">
                                                        </figure>
                                                    </div>
                                                @endif
                                                <div class="rt_sub sub_desc mt-3">{!! $blogs->description !!}</div>
                                                <div class="news-feed-wrapper">
                                                    @foreach ($liveBlogs as $liveBlog)
                                                        @php
                                                            $get_liveBlogs_author = App\Models\User::find(
                                                                $liveBlog->author,
                                                            );
                                                        @endphp
                                                        <div class="news-card">
                                                            <div class="news-time">
                                                                <span
                                                                    class="time">{{ \Carbon\Carbon::parse($liveBlog->created_at)->format('h:i A') }}</span>
                                                                <span
                                                                    class="ago">({{ \Carbon\Carbon::parse($liveBlog->created_at)->diffForHumans(null, true, 'hi') }}
                                                                    पहले)</span>
                                                            </div>
                                                            <div class="news-content">
                                                                <h4 class="news-title">
                                                                    {{ $liveBlog->update_title }}
                                                                    {{-- <span
                                                                        class="news-title-hindi">{{ $liveBlog->update_title }}</span> --}}
                                                                </h4>
                                                                <p class="news-meta"><strong>Posted by:</strong>
                                                                    {{ $get_liveBlogs_author->name }}</p>
                                                                <p class="news-body">{!! $liveBlog->update_content !!}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col_right">
                                        {{-- - 10 latest articles displayed - --}}
                                        @include('components.latestStories')

                                        {{-- Vertical-Small-1 Advertise --}}
                                        <x-vertical-sm-ad :ad="$liveDetailAds['detail_sidebar_vertical_ad1'] ?? null" />

                                        {{-- Side Widgets --}}
                                        <?php
                                        $categories = [['name' => 'क्या कहता है कानून?', 'limit' => 3], ['name' => 'पॉडकास्ट', 'limit' => 1], ['name' => 'टेक्नोलॉजी', 'limit' => 5], ['name' => 'स्पेशल्स', 'limit' => 5]];
                                        ?>
                                        @foreach ($categories as $cat)
                                            <?php
                                            $category = App\Models\Category::where('name', $cat['name'])->first();
                                            $blogs = App\Models\Blog::where('status', '1')->where('categories_ids', $category->id)->orderBy('updated_at', 'DESC')->limit($cat['limit'])->get()->all();
                                            ?>
                                            @include('components.side-widgets', [
                                                'categoryName' => $cat['name'],
                                                'category' => $category,
                                                'blogs' => $blogs,
                                            ])
                                        @endforeach

                                        {{-- Vertical-Small-2 Advertise --}}
                                        <x-vertical-sm-ad :ad="$liveDetailAds['detail_sidebar_vertical_ad2'] ?? null" />

                                    </div>
                                </div>

                                {{-- Horizontal-2 Advertise --}}
                                <x-horizontal-ad :ad="$liveDetailAds['detail_bottom_ad'] ?? null" />

                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
