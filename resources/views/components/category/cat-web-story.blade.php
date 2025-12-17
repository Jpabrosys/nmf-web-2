<div class="cstm-story-container">
    <h3>वेब स्टोरीज</h3>
    <div class="swiper web_s_all_slider">
        <div class="swiper-wrapper  web_s_all_wrap">
            @foreach ($webStories as $stories)
                <?php
                    $category_url = isset($stories->category) ? $stories->category->site_url : '';
                ?>
                {{-- <div class="swiper-slide web_s_all">
                    <a href="{{ asset('/web-stories/' . $category_url . '/' . $stories->siteurl) }}" target="_blank"
                        class="story-card">
                        <div class="story_img">
                            <img src="https://stgn.newsnmf.com/file/webstories/2025/06/tea11749708345.jpg"
                                alt="{{ $stories->name }}">
                            <span class="reals-icon"></span>
                        </div>
                        <p>{{ $stories->name }}</p>
                    </a>
                </div> --}}
                <div class="swiper-slide web_s_all">
                    <a href="{{ asset('/web-stories/' . $category_url . '/' . $stories->siteurl) }}" target="_blank"
                        class="story-card">
                        <div class="story_img">
                            <img src="{{ cached_webstory_image($stories) }}" alt="{{ $stories->name }}" loading="lazy">
                            <span class="reals-icon"></span>
                        </div>
                        <p>{{ $stories->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="web_s_all_next">
            <i class="fas fa-chevron-right"></i>
        </div>
        <div class="web_s_all_prev">
            <i class="fas fa-chevron-left"></i>
        </div>

    </div>
</div>
