<div class="web-stories-block">
    <div class="cm-container">
        <div class="web-stories">
            <div class="story-title">
                <h2>वेब स्टोरीज़</h2>
                <a href="/web-stories" class="see-more-btn2">और देखें <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="stories-container">
                <div class="swiper-wrapper-outer">
                <div class="swiper swiper2 swp-main">
                    <div class="swiper-wrapper">
                        @foreach ($webStories as $stories)
                            <?php
                            $story_file_path = '';
                            $story_file = $stories->webStoryFiles->first();
                            $get_baseUrl = config('global.base_url_web_stories');
                            if ($story_file) {
                                $story_file_name = $story_file->filepath;
                                if (strpos($story_file_name, 'file') !== false) {
                                    $findfilepos = strpos($story_file_name, 'file');
                                    $story_file_path = substr($story_file_name, $findfilepos);
                                    $story_file_path = $get_baseUrl . $story_file_path . '/' . $story_file->filename;
                                }
                            }
                            ?>
                           <div class="swiper-slide web_s">
                            <a href="{{ asset('/web-stories/' . ($stories->category->site_url ?? '') . '/' . $stories->siteurl) }}"
                                target="_blank" class="category-thumbnail">
                                <img src="{{ ($story_file_path) }}" alt="{{ $stories->name }}" loading="lazy">
                                <span class="reals-icon"></span>
                                <h3>{{ $stories->name }}</h3>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev --prev"></div>
                    <div class="swiper-button-next --next"></div>
                </div>
                
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script> --}}
<script>
    // This listener makes the code wait until the entire page (including footer scripts) is ready
    window.addEventListener('load', function() {
        
        const swipernew = new Swiper(".swp-main", {
            direction: "horizontal",
            loop: true,
            slidesPerView: 2.07,
            spaceBetween: 1,
            allowTouchMove: true,

            pagination: {
                el: ".swiper-pagination",
            },

            scrollbar: {
                el: ".swiper-scrollbar",
            },

            breakpoints: {
                0: {
                    navigation: false,
                },
                600: {
                    slidesPerView: 5,
                    spaceBetween: 5,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                }
            }
        });

    });
</script>
