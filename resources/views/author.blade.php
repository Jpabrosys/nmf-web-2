@extends('layouts.app')

@section('content')
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
                                    <li itemprop="itemListElement" itemscope="" itemtype=""
                                        class="trail-item trail-end"><a href="#" itemprop="item"><span
                                                itemprop="name">Author</span></a>
                                        <meta itemprop="position" content="3">
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- Horizontal-1 Advertise --}}
                        <x-horizontal-ad :ad="$authorAds['category_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="news_sub_wrap pt-0">
                                            <div class="author-card">
                                                <div class="author-card-left">
                                                    <img src="{{ $users->image ? asset('file/' . $users->image) : asset('asset/images/nmf-author.webp') }}"
                                                        class="author-image" alt="{{ $users->name }}" width="130"
                                                        height="130">


                                                </div>


                                                <div class="author-card-right">
                                                    <div class="text_wrap">
                                                        <h2>{{ $users->name ?? '' }}</h2>

                                                        <div class="ath-wrap">
                                                            {{-- Email Icon --}}
                                                            <a href="mailto:{{ $users->email }}" class="author-email"
                                                                title="{{ $users->email }}">
                                                                <i class="fa-solid fa-envelope"></i>
                                                            </a>

                                                            {{-- Twitter Icon --}}
                                                            @php
                                                                $defaultTwitterLink = 'https://x.com/NMFNewsOfficial';
                                                                $defaultUsername = '@NMFNewsOfficial';

                                                                if (!empty($users->twitter_link)) {
                                                                    $path = parse_url(
                                                                        $users->twitter_link,
                                                                        PHP_URL_PATH,
                                                                    );
                                                                    $twitterUsername = '@' . ltrim($path, '/');
                                                                    $twitterLink = $users->twitter_link;
                                                                } else {
                                                                    $twitterUsername = $defaultUsername;
                                                                    $twitterLink = $defaultTwitterLink;
                                                                }
                                                            @endphp

                                                            <a class="twitter-link" href="{{ $twitterLink }}"
                                                                target="_blank" title="{{ $twitterUsername }}">
                                                                <i class="fa-brands fa-x-twitter"></i>
                                                            </a>
                                                        </div>

                                                    </div>


                                                    <div class="author-desc">
                                                        <p>
                                                            {{ $users->description ??
                                                                'NMF journalist with over a decade of experience covering politics, global affairs, and human interest stories. Her reporting combines in-depth analysis with a focus on storytelling that resonates with readers across platforms.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="news_auth">
                                                <h3 class="author-t"><span>Stories
                                                        by:&nbsp;&nbsp;{{ isset($users->name) ? $users->name : '' }}</span>
                                                </h3>
                                                @if (isset($users->description))
                                                    <p>
                                                        {{ $users->description }}
                                                    </p>
                                                @endif
                                            </div> --}}
                                            <ul class="nws_list">
                                                @if (count($blogs) > 0)
                                                    @foreach ($blogs as $blog)
                                                        <?php
                                                        $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                        $symbol = '';
                                                        if ($blog->link != '') {
                                                            $symbol = '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;';
                                                        }
                                                        $truncated = $symbol . $blog->name;
                                                        $ff = config('global.blog_images_everywhere')($blog);
                                                        ?>
                                                        <li>
                                                            <article class="nws_article">
                                                                <div class="nws_article_lt">
                                                                    <a
                                                                        href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                                                                        <img @if (!empty($ff)) src="{{ asset($ff) }}" @endif
                                                                            alt="{{ $blog->name }}">
                                                                    </a>
                                                                </div>
                                                                <div class="nws_article_rt">
                                                                    <div class="nws_row">
                                                                        <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                            class="nws_article_strip at_border">{{ $cat->name ?? '' }}</a>
                                                                        <div class="publish_wrap">
                                                                            <div class="publish_dt">
                                                                                <i class="fa-regular fa-calendar-days"></i>
                                                                                <span>{{ $blog->created_at->format('d M, Y') }}</span>
                                                                            </div>
                                                                            <div class="publish_tm">
                                                                                <i class="fa-regular fa-clock"></i>
                                                                                <span>{{ $blog->created_at->format('h:i A') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="nws_title">
                                                                        <a class="t_main"
                                                                            href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($cat->site_url) ? $cat->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?></a>
                                                                        <p class="t_sub">{{ $blog->sort_description }}</p>
                                                                    </div>
                                                                </div>
                                                            </article>
                                                        </li>

                                                        @if ($loop->iteration === 6)
                                                            <li>
                                                                <x-horizontal-sm-ad :ad="$authorAds['category_middle_horz_sm_ad1'] ??
                                                                    null" />
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @else
                                                @endif
                                            </ul>
                                            <div class="nws_pagination">
                                                {{-- Previous Button --}}
                                                @if ($blogs->onFirstPage())
                                                    <span class="page-btn prev disabled">Previous</span>
                                                @else
                                                    <a href="{{ $blogs->previousPageUrl() }}"
                                                        class="page-btn prev">Previous</a>
                                                @endif

                                                {{-- Page Numbers --}}
                                                @foreach ($blogs->links()->elements as $element)
                                                    @if (is_string($element))
                                                        <span class="page-btn">...</span>
                                                    @endif

                                                    @if (is_array($element))
                                                        @foreach ($element as $page => $url)
                                                            <a href="{{ $url }}"
                                                                class="page-btn {{ $blogs->currentPage() == $page ? 'active' : '' }}">
                                                                {{ $page }}
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                @endforeach

                                                {{-- Next Button --}}
                                                @if ($blogs->hasMorePages())
                                                    <a href="{{ $blogs->nextPageUrl() }}" class="page-btn next">Next</a>
                                                @else
                                                    <span class="page-btn next disabled">Next</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col_right">
                                        {{-- - 10 latest articles displayed - --}}
                                        @include('components.latestStories')

                                        {{-- Vertical-Small-1 Category Advertise --}}
                                        <x-vertical-sm-ad :ad="$authorAds['category_sidebar_vaerical_ad1'] ?? null" />

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

                                        {{-- Vertical-Small-2 Category Advertise --}}
                                        <x-vertical-sm-ad :ad="$authorAds['category_sidebar_vaerical_ad2'] ?? null" />

                                    </div>
                                </div>

                                {{-- Horizontal-2 Advertise --}}
                                <x-horizontal-ad :ad="$authorAds['category_bottom_ad'] ?? null" />

                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection