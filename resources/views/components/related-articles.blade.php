<div class="rel_artcle">
    <!-- Heading row -->
    <div class="d-flex align-items-center justify-content-between mb-3 rel_heading_wrap">
        <div class="rel_heading mb-0">यह भी पढ़ें</div>

        <!-- Arrow wrapper -->
        <div class="rel_arrows d-flex gap-2 position-relative">
            <div class="swiper-button-prev rel-nav-prev" id="rel-nav-prev"></div>
            <div class="swiper-button-next rel-nav-next" id="rel-nav-next"></div>
        </div>
    </div>

    <div class="swiper rel-swiper">
        <div class="swiper-wrapper">
            @foreach ($articles as $latest)
                @php
                    $cat = $latest->category ?? App\Models\Category::find($latest->categories_ids);
   	           //$img = config('global.blog_images_everywhere')($latest);
                    $img  = cached_blog_image($latest); 

                    $cat_url = $cat ? url($cat->site_url) : '#';
                    $url = $cat ? url($cat->site_url . '/' . $latest->site_url) : url($latest->site_url);
                @endphp

                <div class="swiper-slide">
                    <article class="rel_article">
                        <div class="rel_top">
                            <a href="{{ $url }}">
                                <img src="{{ asset($img) }}" alt="{{ $latest->name }}">
                            </a>
                            <a href="{{ $cat_url }}" class="nws_article_strip">
                                {{ $cat->name ?? 'अनजान श्रेणी' }}
                            </a>
                        </div>

                        <div class="rel_bottom">
                            <a href="{{ $url }}" class="rel_link">{{ $latest->name }}</a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</div>
