<?php
use App\Models\Blog;
use App\Models\Clip;
use Illuminate\Support\Str;
?>


<div class="cm-container">
    <div class="news-tabs nwstb">
    <a class="newstab_title" href="{{ $site_url }}">
        <h2>{{ $category_name }}</h2>
    </a>
    <a href="{{ $more_url ?? $site_url }}">अधिक<i class="fa-solid fa-arrow-right"></i></a>
</div>
<div class="news-inner">
    @php
        $allBlogs = Blog::where('status', '1')
            ->where('state_ids', $cat_id)
            ->where('sequence_id', '=', '0')
            ->where('isLive', '=', '0')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        $leftSection = $allBlogs->slice(0, 1); // 1 blog
        $middleSection = $allBlogs->slice(1, 4); // next 4 blogs
        $rightSection = $allBlogs->slice(5, 5);

    @endphp

    <!-- Left Section -->
    @if ($leftSection->isNotEmpty())
        <div class="news-block2">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($leftSection as $blog)
                        <?php
                        $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                        $imageUrl = config('global.blog_images_everywhere')($blog);
                        $blogUrl = isset($blog->site_url) ? asset($categorySlug . '/' . $blog->site_url) : '';
                        ?>

                        <div class="swiper-slide">
                            <div class="swiper_card">
                                <div class="swiper_card_top">
                                    <a href="{{ $blogUrl }}">
                                        <img @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                            alt="{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}"
                                            loading="lazy">
                                    </a>
                                    <div class="category_strip">
                                        <a href="{{ asset($categorySlug) }}"
                                            class="category">{{ $blog->category->name ?? '' }}</a>
                                    </div>
                                </div>
                                <div class="swiper_card_bottom">
                                    <a href="{{ $blogUrl }}">
                                        @if (!empty($blog->link))
                                            <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        @endif
                                        {{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}
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
            @foreach ($middleSection as $blog)
                @php
                    $categorySlug = $blog->category->site_url ?? '';
                    $imageUrl = config('global.blog_images_everywhere')($blog);
                    $blogUrl = $blog->site_url ? asset($categorySlug . '/' . $blog->site_url) : '';
                @endphp
                <div class="custom-tab-card ctn2">
                    <a class="--ct_img" href="{{ $blogUrl }}">
                        <img @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                            alt="{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}"
                            loading="lazy">
                    </a>
                    <div class="custom--card-title">
                        <a href="{{ $blogUrl }}">
                            @if (!empty($blog->link))
                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                            @endif
                            {{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}
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
            @foreach ($rightSection as $blog)
                @php
                    $categorySlug = $blog->category->site_url ?? '';
                    $blogUrl = $blog->site_url ? asset($categorySlug . '/' . $blog->site_url) : '';
                @endphp
                <div class="news_desc p_2">
                    <a href="{{ $blogUrl }}">
                        @if (!empty($blog->link))
                            <i class="fa fa-video-camera" aria-hidden="true"></i>
                        @endif
                        {{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}
                    </a>
                </div>
            @endforeach

        </div>
    @endif

    <!-- Right Section End -->

</div>

</div>