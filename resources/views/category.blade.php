  <?php
  use App\Models\HomeSection;
  use App\Models\ElectionResult;
  use App\Models\MahaMukabla;
  use App\Models\Candidate;
  use App\Models\Party;
  ?>

  @php

      $showMaha = HomeSection::where('title', 'ElectionMahaSection')->where('status', 1)->exists();
      $showLive = HomeSection::where('title', 'ElectionLiveSection')->where('status', 1)->exists();
      $showExitPoll = HomeSection::where('title', 'ExitPollSection')->where('status', 1)->exists();
  @endphp

  @extends('layouts.app')
  @section('content')
      <link rel="stylesheet" href="{{ asset('/asset/css/dharm-gyan.css') }}" type="text/css" media="all" />
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

      <div class="inner-page-wrapper" style="transform: none;">
          <div id="primary" class="content-area" style="transform: none;">
              <main id="main" class="site-main" style="transform: none;">
                  <div class="cm_archive_page" style="transform: none;">

                      <div class="cm-container">
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
                                      <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                          class="trail-item trail-end"><a
                                              href="{{ asset('/') }}{{ isset($category->site_url) ? $category->site_url : '-' }}"
                                              itemprop="item"><span
                                                  itemprop="name">{{ isset($category->name) ? $category->name : '' }}</span></a>
                                          <meta itemprop="position" content="3">
                                      </li>
                                  </ul>
                              </nav>
                          </div>
                      </div>
                      <?php
                      $bidhansabhacatname = $category->name;
                      ?>
                       @if (trim($bidhansabhacatname) === 'विधानसभा चुनाव')
                      <section class="election-section-live">
                          @if ($showExitPoll)
                               @include('components.election-exit-poll')
                              <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />
                          @elseif($showLive)
                              @include('components.election-live-section')
                              <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />
                          @endif

                      </section>
                      @endif
                      {{-- ============================================= --}}
                      {{-- NL1043: 08.10.2025 : added --}}
                      @if (trim($bidhansabhacatname) === 'विधानसभा चुनाव')
                          <section class="sdle-section">
                              <div class="cm-container">
                                  <div class="sdle-block">
                                      <img class="sdle-img" src="{{ asset('asset/images/election-schedule-bg.jpg') }}"
                                          alt="Other Parties Logo">
                                      <div class="sdle-title-box">
                                          <div class="sdle-title1">बिहार विधानसभा चुनाव 2025 शेड्यूल</div>
                                          {{-- <div class="sdle-title2">बिहार विधानसभा चुनाव 2025 शेड्यूल</div> --}}
                                      </div>
                                      <div class="sdle-wrap">
                                          <div class="sdle-left">
                                              <img src="{{ asset('asset/images/bihar-map.png') }}" alt="Bihar map">
                                          </div>
                                          <div class="sdle-mid">
                                              <div class="phase-wrap">
                                                  <div class="phase-l">
                                                      <img class="vote-poll"
                                                          src="{{ asset('asset/images/vote-graph.png') }}" alt="Vote poll">
                                                      <div class="phase-date pd-boder">
                                                          <div class="phs-1">
                                                              <h3>फेज 1</h3>
                                                              <h3>6 नवंबर - 121 सीटें</h3>
                                                          </div>
                                                          <a class="phase-details-btn ps-btn1"
                                                              href="https://stgn.newsnmf.com/bihar-election-2025-phase-1">Full
                                                              Details</a>
                                                      </div>
                                                      <div class="phase-date">
                                                          <div class="phs-2">
                                                              <h3>फेज 2</h3>
                                                              <h3>11 नवंबर - 122 सीटें</h3>
                                                          </div>
                                                          <a class="phase-details-btn ps-btn2"
                                                              href="https://stgn.newsnmf.com/bihar-election-2025-phase-2">Full
                                                              Details</a>
                                                      </div>
                                                  </div>
                                                  <div class="phase-r"><img
                                                          src="{{ asset('asset/images/grahics-14nov.jpg') }}"
                                                          alt="voting-date"></div>
                                              </div>
                                          </div>
                                          <div class="sdle-right">
                                              <img src="{{ asset('asset/images/chair.png') }}" alt="chair">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </section>
                        

                          <!-- Second visit -->
                          <section class="maha-section">
                              {{-- show mahamukabla --}}
                              @if ($showMaha)
                                  <div class="mt-3">
                                      @include('components.election-maha-section')
                                  </div>
                              @endif
                          </section>
                      @endif

                      {{-- Horizontal-1 Advertise --}}
                      <x-horizontal-ad :ad="$categoryAds['category_header_ad'] ?? null" />

                      <section class="news_main_section">
                          <div class="cm-container">
                              <div class="news_main_row">
                                  <div class="col_left">
                                      <div class="news_main_wrap">
                                          <div class="nws-left">
                                              @if (count($topBlogs) > 0)
                                                  <?php
                                                  $blog = $topBlogs->first();
                                                  $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                  $symbol = $blog->link ? '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;' : '';
                                                  $truncated = $symbol . $blog->name;
                                                  //$ff = config('global.blog_images_everywhere')($blog);
                                                  $ff = cached_blog_image($blog);
                                                  ?>
                                                  <div class="nws_card">
                                                      <div class="nws_card_top dg_top">
                                                          <a
                                                              href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                                                              <img @if (!empty($ff)) src="{{ asset($ff) }}" @endif
                                                                  alt="{{ $blog->name }}">
                                                          </a>
                                                          <div class="category_strip">
                                                              @if (isset($cat->name) && $cat->name == 'राज्य' && isset($blog->state->name))
                                                                  <a href="{{ asset('/') }}{{ 'state' }}/{{ $blog->state->site_url }}"
                                                                      class="category">{{ $blog->state->name }}</a>
                                                                  {{-- @else
                                                                    <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                        class="category">{{ $cat->name ?? '' }}
                                                                    </a> --}}
                                                              @endif
                                                          </div>
                                                      </div>
                                                      <div class="nws_card_bottom">
                                                          <a
                                                              href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($category->site_url) ? $category->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?>
                                                          </a>
                                                      </div>
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
                                              @endif
                                          </div>

                                          <div class="nws-right">
                                              @foreach ($topBlogs->skip(1)->take(4) as $blog)
                                                  <?php
                                                  $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                  //$ff = config('global.blog_images_everywhere')($blog);
                                                  $ff = cached_blog_image($blog);
                                                  $symbol = $blog->link ? '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;' : '';
                                                  $truncated = $symbol . $blog->name;
                                                  ?>
                                                  <div class="custom-tab-card">
                                                      <a class="custom-img-link"
                                                          href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                                                          <img @if (!empty($ff)) src="{{ asset($ff) }}" @endif
                                                              alt="{{ $blog->name }}">
                                                      </a>
                                                      <div class="custom-tab-title">
                                                          @if (isset($cat->name) && $cat->name == 'राज्य' && isset($blog->state->name))
                                                              <a href="{{ asset('/') }}{{ 'state' }}/{{ $blog->state->site_url }}"
                                                                  class="nws_article_strip">{{ $blog->state->name }}</a>
                                                              {{-- @else
                                                                <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                    class="nws_article_strip">{{ $cat->name ?? '' }}
                                                                </a> --}}
                                                          @endif
                                                          <a id="cat-t"
                                                              href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($category->site_url) ? $category->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?>
                                                          </a>
                                                      </div>
                                                  </div>
                                              @endforeach
                                          </div>
                                      </div>
                                      {{-- -Web Stories- --}}
                                      <?php
                                      $webStories = App\Models\WebStories::where('status', '1')->where('categories_id', $category->id)->orderBy('id', 'DESC')->limit(10)->get();
                                      ?>
                                      @if ($webStories->isNotEmpty())
                                          @include('components.category.cat-web-story', [
                                              'webStories' => $webStories,
                                          ])
                                      @endif

                                      <?php
                                      $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
                                      $stateName = '';
                                      if ($state != '') {
                                          $stateObj = App\Models\State::where('site_url', $state)->first();
                                          $stateName = isset($stateObj->name) ? $stateObj->name : '';
                                          $stateName = $stateName == 'नई दिल्ली' ? 'दिल्ली' : $stateName;
                                          $stateUrl = isset($stateObj->site_url) ? $stateObj->site_url : '';
                                      }
                                      $bidhansabha_cat_name = App\Models\Category::where('name', 'विधान सभा चुनाव')->first();
                                      $bidhansabha_cat_url = isset($bidhansabha_cat_name->site_url) ? $bidhansabha_cat_name->site_url : '';
                                      
                                      $subcat = isset($_REQUEST['subcat']) ? $_REQUEST['subcat'] : '';
                                      $subcatName = '';
                                      if ($subcat != '') {
                                          $subcatObj = App\Models\SubCategory::where('site_url', $subcat)->first();
                                          $subcatName = isset($subcatObj->name) ? $subcatObj->name : '';
                                          $subcatUrl = isset($subcatObj->site_url) ? $subcatObj->site_url : '';
                                      }
                                      
                                      $categorySlug = isset($category->site_url) ? $category->site_url : '';
                                      ?>

                                      <div class="news_sub_wrap">
                                          <div class="news_tab_t">
                                              <div class="ntab">
                                                  @if ($stateName)
                                                      <a class="newstab_title"
                                                          href="{{ asset($bidhansabha_cat_url) . '?state=' . $stateUrl }}">
                                                          {{ $stateName }}
                                                          {{ isset($category->name) ? $category->name : '' }}
                                                      </a>
                                                  @elseif ($subcatName)
                                                      <a class="newstab_title"
                                                          href="{{ asset($subcatUrl) . '?subcat=' . $subcatUrl }}">
                                                          {{ isset($category->name) ? $category->name : '' }}->{{ $subcatName }}
                                                      </a>
                                                  @else
                                                      <a class="newstab_title"
                                                          href="{{ asset($categorySlug) }}">{{ isset($category->name) ? $category->name : '' }}
                                                      </a>
                                                  @endif
                                              </div>
                                              <div class="nline">

                                              </div>
                                          </div>
                                          <ul class="nws_list" id="blog-list">
                                              @include('components.category.blog-list', [
                                                  'blogs' => $blogs,
                                                  'categoryAds' => $categoryAds,
                                                  'category' => $category,
                                              ])
                                          </ul>

                                          {{-- NL1031: 19.09.2025 : removed --}}

                                          @if ($blogs->count() > 0)
                                              <div class="text-center my-4">
                                                  <button id="load-more-btn" class="show-more-btn"
                                                      data-offset="{{ $blogs->count() }}"
                                                      data-name="{{ $category->site_url }}"
                                                      data-state="{{ request('state', '') }}"
                                                      data-subcat="{{ request('subcat', '') }}">
                                                      Show More <i class="fa-solid fa-angle-down"></i>
                                                  </button>
                                              </div>
                                          @endif
                                      </div>
                                  </div>
                                  <div class="col_right">
                                      {{-- - 10 latest articles displayed - --}}
                                      @include('components.latestStories')

                                      {{-- Vertical-Small-1 Category Advertise --}}
                                      <x-vertical-sm-ad :ad="$categoryAds['category_sidebar_vaerical_ad1'] ?? null" />

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
                                      <x-vertical-sm-ad :ad="$categoryAds['category_sidebar_vaerical_ad2'] ?? null" />

                                  </div>
                              </div>

                              {{-- NL1043: 08.10.2025 : added --}}

                              @if (trim($bidhansabhacatname) === 'विधानसभा चुनाव')
                                  <section class="election-section">
                                      <div class="cm-container">
                                          <div class="election-wrap ew-m">
                                              <div class="el-img">
                                                  <img src="{{ config('global.base_url_asset') }}asset/images/bihar-map.png"
                                                      alt="Election 2020">
                                              </div>
                                              <div class="el-row">
                                                  <div class="el-left">
                                                      <h3 class="el-title">2020 का परिणाम</h3>
                                                      <div class="result-section">
                                                          <div class="result-box nda">
                                                              <span class="title">NDA</span>
                                                              <span class="count" data-count="125">0</span>
                                                          </div>
                                                          <div class="result-box rjd">
                                                              <span class="title">RJD+</span>
                                                              <span class="count" data-count="110">0</span>
                                                          </div>
                                                          <div class="result-box ljp">
                                                              <span class="title">LJP</span>
                                                              <span class="count" data-count="1">0</span>
                                                          </div>
                                                          <div class="result-box oth">
                                                              <span class="title">OTH</span>
                                                              <span class="count" data-count="7">0</span>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="el-center">
                                                      <table class="party-table">
                                                          <thead>
                                                              <tr>
                                                                  <th>Party</th>
                                                                  <th>Seats</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                                              <tr>
                                                                  <td><img src=" {{ config('global.base_url_asset') }}asset/images/bjp-logo.png"
                                                                          alt="BJP"> BJP</td>
                                                                  <td>74</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src=" {{ config('global.base_url_asset') }}asset/images/jdu-logo.png"
                                                                          alt="JDU"> JDU</td>
                                                                  <td>43</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/rjd-logo.png"
                                                                          alt="RJD"> RJD</td>
                                                                  <td>75</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/inc-logo.png"
                                                                          alt="INC"> INC</td>
                                                                  <td>19</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/oth-logo.png"
                                                                          alt="OTH"> OTH</td>
                                                                  <td>32</td>
                                                              </tr>
                                                          </tbody>
                                                      </table>
                                                  </div>
                                                  <div class="el-right">

                                                      <div class="chart-container">
                                                          <div class="win-mark">
                                                              <div class="win-t"></div>
                                                              <div class="win-l"></div>
                                                          </div>
                                                          <canvas id="semiCircleChartNew"></canvas>
                                                          <!-- <div class="legend" id="chartLegend"></div> -->
                                                          <div class="total-seats">
                                                              <p>Total Seats</p>
                                                              <h3>243 seats</h3>
                                                          </div>
                                                      </div>

                                                  </div>
                                              </div>
                                          </div>

                                          <div class="election-wrap ew-m2">
                                              <div class="el-img">
                                                  <img src="{{ config('global.base_url_asset') }}asset/images/bihar-map.png"
                                                      alt="Election 2020">
                                              </div>
                                              <h3 class="el-title">2020 का परिणाम</h3>
                                              <div class="el-row-m">
                                                  <div class="el-left-m">
                                                      <div class="result-section">
                                                          <div class="result-box nda">
                                                              <span class="title">NDA</span>
                                                              <span class="count" data-count="125">0</span>
                                                          </div>
                                                          <div class="result-box rjd">
                                                              <span class="title">RJD+</span>
                                                              <span class="count" data-count="110">0</span>
                                                          </div>
                                                          <div class="result-box ljp">
                                                              <span class="title">LJP</span>
                                                              <span class="count" data-count="1">0</span>
                                                          </div>
                                                          <div class="result-box oth">
                                                              <span class="title">OTH</span>
                                                              <span class="count" data-count="7">0</span>
                                                          </div>
                                                      </div>
                                                      <table class="party-table">
                                                          <thead>
                                                              <tr>
                                                                  <th>Party</th>
                                                                  <th>Seats</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                                              <tr>
                                                                  <td><img src=" {{ config('global.base_url_asset') }}asset/images/bjp-logo.png"
                                                                          alt="BJP"> BJP</td>
                                                                  <td>74</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src=" {{ config('global.base_url_asset') }}asset/images/jdu-logo.png"
                                                                          alt="JDU"> JDU</td>
                                                                  <td>43</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/rjd-logo.png"
                                                                          alt="RJD"> RJD</td>
                                                                  <td>75</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/inc-logo.png"
                                                                          alt="INC"> INC</td>
                                                                  <td>19</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/oth-logo.png"
                                                                          alt="OTH"> OTH</td>
                                                                  <td>32</td>
                                                              </tr>
                                                          </tbody>
                                                      </table>
                                                  </div>
                                                  <div class="el-right-m">

                                                      <div class="chart-container2">
                                                          <div class="win-mark">
                                                              <div class="win-t"></div>
                                                              <div class="win-l"></div>
                                                          </div>
                                                          <canvas id="semiCircleChart2"></canvas>
                                                          <!-- <div class="legend" id="chartLegend"></div> -->
                                                          <div class="total-seats">
                                                              <p>Total Seats</p>
                                                              <h3>243 seats</h3>
                                                          </div>
                                                      </div>


                                                  </div>
                                                  .
                                              </div>
                                          </div>

                                      </div>

                                  </section>
                              @endif
                              {{-- Horizontal-2 Advertise --}}
                              <x-horizontal-ad :ad="$categoryAds['category_bottom_ad'] ?? null" />

                          </div>
                      </section>

                  </div>
              </main>
          </div>
      </div>
      {{-- added===== --}}
    {{--   @php
          $results3 = $topParties->take(4)->map(function ($p) {
              return [
                  'party_name' => $p->party_name,
                  'abbreviation' => $p->abbreviation,
                  'seats_won' => $p->seats_won,
              ];
          });
      @endphp  --}}


      {{-- <script>
          const results3 = @json($results3);
      </script>   --}}

      {{-- added end====== --}}

      {{-- NL1031: 19.09.2025 : added chart js --}}
      {{-- Chart.js --}}
      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0"></script>
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

      <script>
          // maha mukabla swiper
          const swiper = new Swiper('.mh-carousel', {
              loop: true,
              navigation: {
                  nextEl: '.mh-button-next',
                  prevEl: '.mh-button-prev',
              },
              autoplay: {
                  delay: 3000,
                  disableOnInteraction: false,
              },
          });
          document.addEventListener("DOMContentLoaded", function() {
              // ---------------- COUNTER ----------------
              const counters = document.querySelectorAll(".count");
              counters.forEach(counter => {
                  let target = +counter.getAttribute("data-count");
                  let current = 0;
                  let increment = Math.ceil(target / 50);

                  let interval = setInterval(() => {
                      current += increment;
                      if (current >= target) {
                          current = target;
                          clearInterval(interval);
                      }
                      counter.textContent = current.toString().padStart(2, '0');
                  }, 30);
              });

              // ---------------- SEMI-CIRCLE CHART FUNCTION ----------------
              if (window.Chart && window.ChartDataLabels && !Chart._nmfDataLabelsRegistered) {
                  Chart.register(ChartDataLabels);
                  Chart._nmfDataLabelsRegistered = true;
              }

              function createSemiCircleChart(canvasId, results, options = {}) {
                  const canvas = document.getElementById(canvasId);
                  if (!canvas) return null;

                  // destroy old instance
                  if (canvas._chartInstance) {
                      try {
                          canvas._chartInstance.destroy();
                      } catch (e) {}
                      canvas._chartInstance = null;
                  }

                  // Filter out LJP
                  const filteredResults = results.filter(r => r.abbreviation.toLowerCase() !== 'ljp');

                  // Prepare labels, values, and colors
                  const labels = filteredResults.map(r => r.party_name);
                  const values = filteredResults.map(r => r.seats_won);
                  const colorMap = {
                      'nda': '#fd6101',
                      'rjd': '#13B605',
                      'jsp': '#FABB00',
                      'oth': '#D13A37'
                  };
                  const colors = filteredResults.map(r => colorMap[r.abbreviation.toLowerCase()] || '#13B605');
                  const aspectRatio = (typeof options.aspectRatio !== 'undefined') ?
                      options.aspectRatio :
                      (window.innerWidth < 768 ? 1 : 1.5);

                  const config = {
                      type: 'doughnut',
                      data: {
                          labels: labels,
                          datasets: [{
                              data: values,
                              backgroundColor: colors,
                              borderWidth: 2,
                              borderColor: 'white',
                              hoverOffset: 15,
                              borderRadius: 4
                          }]
                      },
                      options: {
                          responsive: true,
                          maintainAspectRatio: true,
                          aspectRatio: aspectRatio,
                          rotation: -90,
                          circumference: 180,
                          cutout: options.cutout || '60%',
                          animation: {
                              duration: options.duration || 600
                          },
                          plugins: {
                              legend: {
                                  display: false
                              },
                              // NL1031:16Sep2025: Disable tooltip
                              tooltip: {
                                  enabled: false,
                                  callbacks: {
                                      label: function(context) {
                                          const label = context.label || '';
                                          const value = context.raw || 0;
                                          const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                          const percentage = total ? Math.round((value / total) * 100) : 0;
                                          return `${label}: ${percentage}%`;
                                      }
                                  }
                              },
                              datalabels: {
                                  color: 'black',
                                  font: {
                                      weight: 'bold',
                                      size: options.datalabelSize || 14
                                  },
                                  formatter: () => ''
                              }
                          }
                      },
                      plugins: []
                  };

                  if (window.ChartDataLabels) config.plugins.push(ChartDataLabels);

                  const chartInstance = new Chart(canvas.getContext('2d'), config);
                  canvas._chartInstance = chartInstance;
                  return chartInstance;
              }

              // Example datasets
              const results1 = [{
                      party_name: "NDA",
                      seats_won: 87,
                      abbreviation: "nda"
                  },
                  {
                      party_name: "RJD+",
                      seats_won: 53,
                      abbreviation: "rjd"
                  },
                  {
                      party_name: "LJP",
                      seats_won: 5,
                      abbreviation: "ljp"
                  },
                  {
                      party_name: "OTH",
                      seats_won: 9,
                      abbreviation: "oth"
                  }
              ];

              const results2 = [{
                      party_name: "NDA",
                      seats_won: 87,
                      abbreviation: "nda"
                  },
                  {
                      party_name: "RJD+",
                      seats_won: 53,
                      abbreviation: "rjd"
                  },
                  {
                      party_name: "LJP",
                      seats_won: 5,
                      abbreviation: "ljp"
                  },
                  {
                      party_name: "OTH",
                      seats_won: 9,
                      abbreviation: "oth"
                  }
              ];

              const results3 = [{
                      party_name: "NDA",
                      seats_won: 90,
                      abbreviation: "nda"
                  },
                  {
                      party_name: "RJD+",
                      seats_won: 50,
                      abbreviation: "rjd"
                  },
                  {
                      party_name: "LJP",
                      seats_won: 3,
                      abbreviation: "ljp"
                  },
                  {
                      party_name: "OTH",
                      seats_won: 10,
                      abbreviation: "oth"
                  }
              ];

              // ✅ New dataset for the new chart
              const resultsNew = [{
                      party_name: "NDA",
                      seats_won: 95,
                      abbreviation: "nda"
                  },
                  {
                      party_name: "RJD+",
                      seats_won: 70,
                      abbreviation: "rjd"
                  },
                  {
                      party_name: "OTH",
                      seats_won: 20,
                      abbreviation: "oth"
                  }
              ];

              // init charts
              createSemiCircleChart('semiCircleChartNew', resultsNew, {
                  duration: 500
              });

              // init charts
              createSemiCircleChart('semiCircleChart', results1, {
                  duration: 500
              });
              createSemiCircleChart('semiCircleChart2', results2, {
                  duration: 500
              });
              createSemiCircleChart('semiCircleChart3', results3, {
                  duration: 500
              });


              // re-render on resize (debounced)
              let resizeTimer;
              window.addEventListener('resize', function() {
                  clearTimeout(resizeTimer);
                  resizeTimer = setTimeout(function() {
                      createSemiCircleChart('semiCircleChartNew', resultsNew);
                      createSemiCircleChart('semiCircleChart', results1);
                      createSemiCircleChart('semiCircleChart2', results2);
                      createSemiCircleChart('semiCircleChart3', results3);
                  }, 200);
              });
          });
      </script>

      <script>
          const swipernew = new Swiper('.swiper2', {
              direction: 'horizontal',
              loop: true,
              slidesPerView: 5,
              spaceBetween: 10,


              pagination: {
                  el: '.swiper-pagination',
                  clickable: true,
              },

              navigation: {
                  nextEl: '.swiper-button-next',
                  prevEl: '.swiper-button-prev',
              },


              scrollbar: {
                  el: '.swiper-scrollbar',
              },


              breakpoints: {
                  640: {
                      slidesPerView: 2,
                      spaceBetween: 10,
                  },
                  768: {
                      slidesPerView: 3,
                      spaceBetween: 20,
                  },
                  1024: {
                      slidesPerView: 5,
                      spaceBetween: 10,
                  },
              },
          });
      </script>


  @endsection
