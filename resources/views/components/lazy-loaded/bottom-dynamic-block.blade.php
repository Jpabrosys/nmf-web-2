@php $loopCounter = 0; @endphp

@foreach ($sectionCategories as $index => $section)
    @if ($index >= 15 && !empty($section) && isset($section['site_url'], $section['name'], $section['catid']))
        @php 
            $loopCounter++;
            $layoutType = $index % 4;
        @endphp

        @switch($layoutType)
            @case(0)
                @include('components.news-nine-style', [
                    'cat_id' => $section['catid'],
                    'cat_name' => $section['name'],
                    'cat_site_url' => $section['site_url'],
                ])
            @break

            @case(1)
                @include('components.slider-two-news-5', [
                    'cat_id' => $section['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $section['site_url'],
                    'category_name' => $section['name'],
                ])
            @break

            @case(2)
                @include('components.news-nine-style', [
                    'cat_id' => $section['catid'],
                    'cat_name' => $section['name'],
                    'cat_site_url' => $section['site_url'],
                ])
            @break

            @case(3)
                @include('components.slider-two-news-5', [
                    'cat_id' => $section['catid'],
                    'leftTitle' => 'ताजा खबर',
                    'middleTitle' => 'शीर्ष समाचार',
                    'rightTitle' => 'वीडियो',
                    'site_url' => $section['site_url'],
                    'category_name' => $section['name'],
                ])
            @break
        @endswitch

        @if ($loopCounter % 3 === 0)
            <x-horizontal-ad :ad="$homeAds['home_bottom_ad'] ?? null" />
        @endif
    @endif
@endforeach