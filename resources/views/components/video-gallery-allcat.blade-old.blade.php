<?php
use App\Models\Blog;
use App\Models\Video;
//use App\Models\Category;

//$categories = Category::where('home_page_status', '1')->get();
?>
<div class="cm-container video_block">
    {{-- <a href="{{ asset('videos') }}" class="vdo_title">वीडियो गैलरी</a> --}}
    @php
        // Fetch all video blogs with a non-null link and group them by category ID
        // $videoBlogs = Blog::where('status', '1')
        //     ->whereNotNull('link')
        //     ->orderByDesc('id')
        //     ->take(30)
        //     ->get()
        //     ->groupBy('categories_ids');

        // Extract unique category IDs
        //$uniqueCategoryIds = $videoBlogs->keys();

        // Prepare tab data
        //$tabData = [];

        // "सभी" (All) tab
        $allVideoBlogs = Blog::where('status', '1')
            ->whereNotNull('link')
            ->where('sequence_id', '0')
            ->where('isLive', '0')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        $allVideo = Video::where('is_active', '1')
            ->orderByDesc('id')
            ->take(10)
            ->get();


        // Merge and sort by latest
        $combined = $allVideo->concat($allVideoBlogs)->sortByDesc('created_at')->values();

        // if ($allVideoBlogs->isNotEmpty()) {
        //     $tabData[] = [
        //         'label' => 'सभी',
        //         'id' => 'cat0',
        //         'blogs' => $allVideoBlogs,
        //         'active' => true,
        //     ];
        // }

        // Tabs for each category
        // $tabIndex = 1;
        // foreach ($uniqueCategoryIds as $catid) {
        //     $category = $categories->firstWhere('id', $catid);
        //     if ($category) {
        //         $blogs = Blog::where('status', '1')
        //             ->where('categories_ids', $catid)
        //             ->whereNotNull('link')
        //             ->where('sequence_id', '0')
        //             ->where('isLive', '0')
        //             ->orderByDesc('id')
        //             ->get();

        //         if ($blogs->isNotEmpty()) {
        //             $tabData[] = [
        //                 'label' => $category->name,
        //                 'id' => 'cat' . $tabIndex,
        //                 'blogs' => $blogs,
        //                 'active' => false,
        //             ];
        //             $tabIndex++;
        //         }
        //     }
        // }

    @endphp


    {{-- @foreach ($tabData as $tab)
            <button class="tab-btn {{ $tab['active'] ? 'active' : '' }}" data-tab="{{ $tab['id'] }}">
                <span>{{ $tab['label'] }}</span>
            </button>
        @endforeach --}}
    <div class="vdo_title">
        <h2>वीडियो</h2> <a href="{{ asset('videos') }}" class="see-more-btn2">
            और देखें <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>


    {{-- @foreach ($tabData as $tab) --}}
    {{-- <div class="tab-content {{ $tab['active'] ? 'active' : '' }}" id="{{ $tab['id'] }}"> --}}
    <div class="vdo_content">
        <div class="video-items-left">
            @foreach ($combined->take(4) as $blog)
                @if ($blog instanceof \App\Models\Video)
                    @php
                        $categorySlug = $blog->category->site_url ?? '';
                        $categoryName = optional($blog->category)->name ?? '';
                        $imageUrl = asset($blog->thumbnail_path);
                        $blogUrl = $blog->site_url ? asset('video/' . $categorySlug . '/' . $blog->site_url) : '';
                        //dd($blogUrl, $imageUrl);

                    @endphp
                    <div class="vdo_card">
                        <div class="vdo_card_top">
                            <a href="{{ $blogUrl }}" class="vdo_card_img">
                                <img class="thumbnail" loading="lazy"
                                    @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                    alt="{{ $blog->title }}">
                            </a>
                            <div class="playBtn-wrap">
                                    <a href="{{ $blogUrl }}" class="play-btn"><i class="fa-solid fa-play"></i></a>
                                    <div class="v-b"></div>
                                    <p class="v-duration">{{ $blog->duration }}</p>
                                </div>
                        
                            {{-- <a href="{{ $categorySlug }}" class="category_strip"><span
                                    class="category">{{ $categoryName }}</span></a> --}}
                        </div>
                        <div class="vdo_card_bottom">
                            <a
                                href="{{ $blogUrl }}">{{ $blog->title }}</a>
                        </div>
                    </div>
                @else
                    @php
                        $categorySlug = $blog->category->site_url ?? '';
                        $categoryName = optional($blog->category)->name ?? '';
                        $imageUrl = config('global.blog_images_everywhere')($blog);
                        $blogUrl = $blog->site_url ? asset($categorySlug . '/' . $blog->site_url) : '';
                    @endphp
                    <div class="vdo_card">
                        <div class="vdo_card_top">
                            <a href="{{ $blogUrl }}" class="vdo_card_img">
                                <img class="thumbnail" loading="lazy"
                                    @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                    alt="{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}">
                            </a>
                            <div class="playBtn-wrap">
                                    <a href="{{ $blogUrl }}" class="play-btn"><i class="fa-solid fa-play"></i></a>
                                    <div class="v-b"></div>
                                    <p class="v-duration"></p>
                                </div>
                        
                            {{-- <a href="{{ $categorySlug }}" class="category_strip"><span
                                    class="category">{{ $categoryName }}</span></a> --}}
                        </div>
                        <div class="vdo_card_bottom">
                            <a
                                href="{{ $blogUrl }}">{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}</a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="video-items-right">
            @foreach ($combined->slice(4, 5) as $blog)
                @if ($blog instanceof \App\Models\Video)
                    @php
                        $categorySlug = $blog->category->site_url ?? '';
                        $categoryName = optional($blog->category)->name ?? '';
                        $imageUrl = asset($blog->thumbnail_path);
                        $blogUrl = $blog->site_url ? asset('video/' . $categorySlug . '/' . $blog->site_url) : '';
                    @endphp
                    <div class="custom-tab-card">
                        <div class="playBtn-wrap2">
                            <div class="play-btn"><i class="fa-solid fa-play"></i></div>
                                <p class="v-duration2">
                                    {{ $blog->duration }}
                                </p>
                            </div>
                        
                        {{-- <a href="{{ $categorySlug }}" class="category_strip_right">
                            <span class="category">{{ $categoryName }}</span>
                        </a> --}}
                        <a href="{{ $blogUrl }}" class="custom-tab-card-link">
                            <img @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                alt="{{ $blog->title  }}"
                                loading="lazy">
                        </a>
                        <div class="custom-tab-title">
                            <a
                                href="{{ $blogUrl }}">{{ $blog->title  }}</a>
                        </div>
                    </div>
                @else
                    @php
                        $categorySlug = $blog->category->site_url ?? '';
                        $categoryName = optional($blog->category)->name ?? '';
                        $imageUrl = config('global.blog_images_everywhere')($blog);
                        $blogUrl = $blog->site_url ? asset($categorySlug . '/' . $blog->site_url) : '';
                    @endphp
                    <div class="custom-tab-card">
                        <div class="playBtn-wrap2">
                            <div class="play-btn"><i class="fa-solid fa-play"></i></div>
                                <p class="v-duration2">
                                </p>
                            </div>
                        
                        {{-- <a href="{{ $categorySlug }}" class="category_strip_right">
                            <span class="category">{{ $categoryName }}</span>
                        </a> --}}
                        <a href="{{ $blogUrl }}" class="custom-tab-card-link">
                            <img @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                alt="{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}"
                                loading="lazy">
                        </a>
                        <div class="custom-tab-title">
                            <a
                                href="{{ $blogUrl }}">{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}</a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- </div> --}}
    {{-- @endforeach --}}
</div>
