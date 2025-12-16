<div class="cm-container">
    <div class="left_and_right_layout_divider">
        <div class="lay_row">
            <div class="cm-col-lg-8 cm-col-12 sticky_portion px-0">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main">
                        @if (!empty($sectionCategories[9]))
                            @include('components.slider-one-news-5', [
                                'cat_id' => $sectionCategories[9]['catid'],
                                'cat_name' => $sectionCategories[9]['name'],
                                'cat_site_url' => $sectionCategories[9]['site_url'],
                            ])
                        @endif

                        <x-horizontal-sm-ad :ad="$homeAds['home_middle_horz_sm_ad1'] ?? null" />

                        @if (!empty($sectionCategories[10]))
                            @include('components.slider-one-news-5', [
                                'cat_id' => $sectionCategories[10]['catid'],
                                'cat_name' => $sectionCategories[10]['name'],
                                'cat_site_url' => $sectionCategories[10]['site_url'],
                            ])
                        @endif

                        @if (!empty($sectionCategories[11]))
                            @include('components.photo-slider', [
                                'cat_id' => $sectionCategories[11]['catid'],
                                'cat_name' => $sectionCategories[11]['name'],
                                'cat_site_url' => $sectionCategories[11]['site_url'],
                            ])
                        @endif

                        @if (!empty($sectionCategories[12]))
                            @include('components.slider-one-news-5', [
                                'cat_id' => $sectionCategories[12]['catid'],
                                'cat_name' => $sectionCategories[12]['name'],
                                'cat_site_url' => $sectionCategories[12]['site_url'],
                            ])
                        @endif

                        <x-horizontal-sm-ad :ad="$homeAds['home_middle_horz_sm_ad2'] ?? null" />

                        @if (!empty($sectionCategories[13]))
                            @include('components.slider-one-news-5', [
                                'cat_id' => $sectionCategories[13]['catid'],
                                'cat_name' => $sectionCategories[13]['name'],
                                'cat_site_url' => $sectionCategories[13]['site_url'],
                            ])
                        @endif
                    </main>
                </div>
            </div>

            <div class="cm-col-lg-4 cm-col-12 sticky_portion px-1">
                <aside id="secondary" class="sidebar-widget-area">
                    <x-vertical-sm-ad :ad="$homeAds['home_sidebar_vertical_ad2'] ?? null" />

                    @foreach ($sidebarCategoriesList as $index => $sidebarCategory)
                        @include('components.sidebar-widget-3news', [
                            'cat_id' => $sidebarCategory['catid'],
                            'cat_name' => $sidebarCategory['name'],
                            'cat_site_url' => $sidebarCategory['site_url'],
                        ])
                        @if ($index === 2)
                            <div id="categories-2" class="widget widget_categories">
                                <div class="news-tab">
                                    @if(!$flags['showVoteInTopNews'])
                                        @include('components.vote')
                                    @else
                                        @include('components.podcast')
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <x-vertical-sm-ad :ad="$homeAds['home_sidebar_vertical_ad3'] ?? null" />
                </aside>
            </div>
        </div>
    </div>
</div>