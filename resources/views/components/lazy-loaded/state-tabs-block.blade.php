<div class="cm-container">
    <div class="news_tab_row">
        <div class="_devider">
            <div class="left_content news_tabs">
                @if (!empty($rajyaSection))
                    @include('components.all-states-tab', [
                        'cat_id' => $rajyaSection['catid'],
                        'cat_name' => $rajyaSection['name'],
                        'cat_site_url' => $rajyaSection['site_url'],
                    ])
                @endif

                <x-horizontal-ad :ad="$homeAds['home_below_state_section_ad'] ?? null" />

                @if (!empty($bidhanSabhaSection))
                    @include('components.bidhansabha-states-tab', [
                        'cat_id' => $bidhanSabhaSection['catid'],
                        'cat_name' => $bidhanSabhaSection['name'],
                        'cat_site_url' => $bidhanSabhaSection['site_url'],
                    ])
                @endif
            </div>
        </div>
    </div>
</div>