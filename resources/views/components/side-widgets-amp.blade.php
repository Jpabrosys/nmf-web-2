
@if ($categoryName == 'पॉडकास्ट')
    @php return; @endphp
@endif

<div id="categories-canoon" class="widget widget_categories">

    <div class="news-tabs widget_tab">
        <a href="{{ url($category->site_url) }}" class="newstab_title">
            {{ $category->name ?? '' }}
        </a>
    </div>

    <div class="rel_artcle">

        <div class="swiper-wrapper">

            @foreach ($blogs as $blog)
                @php
                    $truncated = $blog->short_title ?: $blog->name;
                    $ff = cached_blog_image($blog);
                    $url = url(($category->site_url ?? '') . '/' . ($blog->site_url ?? ''));
                    $cat_url = url($category->site_url);
                @endphp

                <div class="swiper-slide">
                    <article class="rel_article">

                        <div class="rel_top">
                            <a href="{{ $url }}">
                                <amp-img src="{{ asset($ff) }}" width="300" height="200"
                                         layout="responsive" alt="{{ $truncated }}">
                                </amp-img>
                            </a>

                            <a href="{{ $cat_url }}" class="nws_article_strip">
                                {{ $category->name ?? '' }}
                            </a>
                        </div>

                        <div class="rel_bottom">
                            <a href="{{ $url }}" class="rel_link">
                                {{ $truncated }}
                            </a>
                        </div>

                    </article>
                </div>

            @endforeach

        </div>

        <a href="{{ url($category->site_url) }}" class="more_btn mt-3">
            अधिक →
        </a>

    </div>

</div>
