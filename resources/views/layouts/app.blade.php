<!doctype html>
<html lang="hi">
<head>
    <?php
    use Carbon\Carbon;
    //NL1028:Added new component to remove the function from app blade for SEO purpose
    use App\View\Components\TitleDescription;
    $setting = App\Models\Setting::where('id', 1)->first(); 
    ?>
    @php
  $metaTitle = isset($data['blog'])
    ? ($data['blog']->name ?? ($setting->site_name ?? ''))
    : (new TitleDescription('title'))->display();

$metaTitle = preg_replace('/\s+/', ' ', $metaTitle);


  $metaDescription = isset($data['blog'])
    ? ($data['blog']->sort_description ?? ($setting->site_name ?? ''))
    : (new TitleDescription('description'))->display();

$metaDescription = preg_replace('/\s+/', ' ', $metaDescription);

@endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ isset($data['blog']->keyword) ? $data['blog']->keyword : (isset($setting->keyword) ? $setting->keyword : '') }}">
    <meta charset="UTF-8">
    @if (!str_contains(strtolower(config('global.base_url')), 'stgn'))
    <meta name="robots" content="index, follow" />
    @endif
    <meta name="language" content="hi" />
    <meta name="googlebot" content="notranslate">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- @if (request()->is('video/*'))
    <meta http-equiv="refresh" content="1200" /> 
@elseif (request()->is('podcast/*'))
    <meta http-equiv="refresh" content="1200" />
@else
    <meta http-equiv="refresh" content="300" />
@endif -->
    
  <!-- Google Tag Manager -->
    @if (config('global.gtm_enabled'))
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ config('global.gtm_id') }}');
    </script>
    @endif
    <?php
    $current = rtrim(url()->current(), '/');
    $home = rtrim(config('global.base_url_frontend'), '/');
    $canonicalUrl = str_replace('/amp', '/', url()->current());
    ?>
    @if ($current != $home)
        <link rel="canonical" href="{{ $home }}">
    @else
        <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif
    @yield('head')
    <!-- NL1025:20Sept:2025:Added config path -->
    <link href="{{config('global.base_url_frontend')}}frontend/images/logo.png" rel="shortcut icon" type="image/x-icon">
    <link href="{{config('global.base_url_asset')}}asset/css/big-breaking.css?v=1.23" rel="stylesheet">
       <!-- NL1025:15Sept:2025:Added Condition to show -->
        <!-- NL1025:20Sept:2025:Added config path in url -->
    @if (config('global.schema_enabled'))
    <script type="application/ld+json">{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "name": "NMF News",
      "url": "{{ config('global.base_url') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ config('global.base_url') }}/search?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "Organization",
      "name": "NMF News",
      "url": "{{ config('global.base_url') }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{config('global.base_url_frontend')}}frontend/images/logo.png",
        "width": 300,
        "height": 60
      },
      "sameAs": [
        "https://www.facebook.com/NMFNewsNational",
        "https://x.com/NMFNewsOfficial",
        "https://www.youtube.com/c/NMFNews/featured",
        "https://www.instagram.com/nmfnewsofficial"
      ]
    }
  ]
}
</script>
@endif
   <!--NL1028:17Sep2025:removed the function for SEO performance -->
    <meta property="fb:app_id" content="3916260501994016" />
    @php
    // Get base URL from your config/global.php
    $baseUrl = config('global.base_url'); // e.g. https://www.newsnmf.com

    // Parse host from that URL
    $host = parse_url($baseUrl, PHP_URL_HOST);  // returns www.newsnmf.com

    // Strip "www." and take the first segment before "."
    $domainOnly = explode('.', str_replace('www.', '', $host))[0]; // newsnmf

    @endphp
    <meta property="og:site_name" content="{{ ucfirst($domainOnly) }}">
    <meta property="og:title" content="{{ $metaTitle }}"/>
    <meta property="og:description" content="{{ $metaDescription }}"/>
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ rtrim(config('global.base_url'), '/') }}{{ request()->getPathInfo() }}" />
    <?php
    $URL= config('global.base_url');
    $baseBreakingNewsUrl = url('/breakingnews');
    //$customImageUrl =  asset('asset/images/NMF_BreakingNews.png');
    $customImageUrl = config('global.base_url_image') . "asset/images/NMF_BreakingNews.png";
   // $ff = config('global.blog_images_everywhere')($data['blog'] ?? null);
   $blog = $data['blog'] ?? null;
   $ff = cached_blog_image($blog);

    
    $imageToUse = $ff;

    if (Str::startsWith($URL, $baseBreakingNewsUrl)) {
        $imageToUse = $customImageUrl;
    }
    ?>
    <meta property="og:image" content="<?php
    // $ff = config('global.blog_images_everywhere')($data['blog'] ?? null);
    echo $imageToUse;
    ?>" />
    <meta property="og:image:type" content="image/jpeg">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@nmfnewsofficial">
    <meta name="twitter:url" content="{{ rtrim(config('global.base_url'), '/') }}{{ request()->getPathInfo() }}" />
    <meta name="twitter:title" content="{{ $metaTitle }}"/>
    <meta name="twitter:description" content="{{ $metaDescription }}"/>
    <meta property="twitter:image:type" content="image/jpeg" />
    <meta property="twitter:image:width" content="660" />
    <meta property="twitter:image:height" content="367" />
    <meta name="twitter:image" content="<?php
    //$ff = config('global.blog_images_everywhere')($data['blog'] ?? null);
    echo $imageToUse;
    ?>" />

    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta name="robots" content="max-image-preview:large" />
    <link rel="dns-prefetch" href="//fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{config('global.base_url_asset')}}asset/css/swiper-bundle.min.css" type="text/css" media="all" />
    <!-- NL1028:15Sept:2025:Commented:Start -->
   <!-- <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" type="text/css" media="all" />-->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{config('global.base_url_asset')}}asset/plugins/bootstrap.min.css" type="text/css" media="all" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@100..900&display=swap"
        rel="stylesheet">
    <script type="text/javascript" src="{{config('global.base_url_asset')}}asset/js/jquery.min.js" id="jquery-core-js"></script>
<!-- Lazy load 3rd-party SDKs -->
<script>    
window.addEventListener('load', function () {
  // Google Ads
  let ads = document.createElement('script');
  ads.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3986924419662120";
  ads.async = true; ads.crossOrigin = "anonymous";
  document.body.appendChild(ads);

  // Facebook SDK
  let fb = document.createElement('script');
  fb.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v20.0";
  fb.async = true; document.body.appendChild(fb);

  // Instagram
  let ig = document.createElement('script');
  ig.src = "https://www.instagram.com/embed.js";
  ig.async = true; document.body.appendChild(ig);

  // Twitter
  let tw = document.createElement('script');
  tw.src = "https://platform.twitter.com/widgets.js";
  tw.async = true; document.body.appendChild(tw);
});
</script>
  <!-- NL1025:15Sept:2025:Commented:Start -->
     <!-- Google Tag Manager -->
   <!--  <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5BSHD2LX');
    </script> -->
    <!-- End Google Tag Manager -->
     <!-- NL1025:15Sept:2025:Commented:End -->

  

    <script type="text/javascript">
    <!-- NL1023 : 16/09/2023 - removed -->
        document.addEventListener("DOMContentLoaded", function() {
            const lazyImages = document.querySelectorAll("img[data-src]");
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute("data-src");
                        observer.unobserve(img);
                    }
                });
            });
            lazyImages.forEach(img => observer.observe(img));
        });
    </script>
  <!-- NL1023 : 16/09/2023 - commented -->
    {{-- <link rel="stylesheet" id="wp-block-library-css" href="{{config('global.base_url_asset')}}asset/style.min.css" type="text/css"
        media="all" /> --}}
	 <!-- NL1028:15Sept:2025:Commented:Start -->
   <!-- <link rel="stylesheet" id="fontAwesome-4-css" href="{{ asset('/asset/fonts/fontAwesome/fontAwesome.min.css') }}"
        type="text/css" media="all" />-->
  <!-- NL1023 : 16/09/2023 - commented -->
    {{-- <link rel="stylesheet" id="feather-icons-css" href="{{config('global.base_url_asset')}}{{ asset/fonts/feather/feather.min.css"
        type="text/css" media="all" /> --}}
	  <!-- NL1023 : 16/09/2023 - removed wp id -->
    <link rel="stylesheet" href="{{config('global.base_url_asset')}}asset/css/main.css?v=1.18"
        type="text/css" media="all" />
    <!-- <link rel="stylesheet" href="{{config('global.base_url_asset')}}asset/css/header.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{config('global.base_url_asset')}}asset/css/footer.css?v=1.1" type="text/css" media="all" /> -->
    <link rel="stylesheet" href="{{config('global.base_url_asset')}}/asset/css/webstory.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{config('global.base_url_asset')}}/asset/css/category.css?v=1.1" type="text/css" media="all" />
        
    <link rel="stylesheet" href="{{config('global.base_url_asset')}}asset/css/style.css" type="text/css" media="all" />
    <style id="theia-sticky-sidebar-stylesheet-TSS">
        .theiaStickySidebar:after {
            content: "";
            display: table;
            clear: both;
        }
        li.item.new a span {color: #ffdf00; font-weight:400}
        .mobile-new {color: #ff0000 !important;font-weight:600}
    </style>
</head>
  <!-- NL1023 : 16/09/2023 - removed wp class -->
<body class="home right-sidebar">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5BSHD2LX" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
     <?php
                                         
                                            
                                            $menus = App\Models\Menu::whereRelation('type', 'type', 'Header')
                                                ->whereRelation('category', 'category', 'User')
                                                ->where([['status', '1'], ['menu_id', 0]])
                                                ->whereNotNull('sequence_id')
                                                ->where('sequence_id', '!=', 0)
                                                ->orderBy('sequence_id', 'asc')
                                                ->get()
                                                ->take(11)
                                                ->toArray();
                                            ?>

    <div class="page-wrapper">

        <header class="--header">
            <div class="cm-container">
                <div class="--header-container">
                    <div class="--header-left">
                        <a href="/" class="--nmf-logo">
                            <!-- NL1025:20Sept:2025:Added config path -->
                            <img src="{{config('global.base_url_frontend')}}frontend/images/logo.png" alt="Logo"
                                class="--logo" />
                        </a>
                    </div>
                    <div class="--header-right">
                        <div class="--header-right-top">
                            <div class="--hdr-top">
                                <div class="--hdr-t-l">

                                    <button class="--toggle-box" id="toggle-btn">
                                        <label class="burger" for="burger">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </label>
                                    </button>
                                    <a href="javascript:void(0);" class="nav-item srch" onclick="openSearchModal()">
                                        <i class="fas fa-search"></i>

                                    </a>

                                    <form class="--search-form" method="get" action="{{ asset('/search') }}">
                                        <div class="input-group">
                                            <input type="search" name="search" class="form-control pt-2 pb-1"
                                                placeholder="Search..." aria-label="Search">
                                            <button type="submit" class="input-group-text">
                                                <i class="fas fa-magnifying-glass"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="--hdr-t-r">
                                    <div class="pod-cast">
                                        <a href="{{ config('global.base_url') }}being-ghumakkad">
                             <!--NL1028:Added:global config path-->
                                            <img loading="lazy" style="width: 30px;"
                                                src="{{ config('global.base_url_image') }}file/bg_icon.png" alt="">
                                        </a>
 
                                        <a href="{{ config('global.base_url') }}Podcast">
                                            <img loading="lazy" style="width: 54px;"
                                                src="{{ config('global.base_url_image') . 'file/podcost_icon.png' }}" alt="">
                                        </a>
                                    </div>
                                    <div class="social-wrap">
                                        <a href="https://www.facebook.com/NMFNewsNational/" target="_blank"
                                            class="social-item"><span><i
                                                    class="fa-brands fa-facebook-f"></i></span></a>
                                        <a href="https://x.com/NMFNewsOfficial" target="_blank"
                                            class="social-item"><span><i class="fa-brands fa-x-twitter"></i>
                                            </span></a>
                                        <a href="https://instagram.com/nmfnewsofficial" target="_blank"
                                            class="social-item"><span><i
                                                    class="fa-brands fa-instagram"></i></span></a>
                                        <a href="https://www.youtube.com/c/NMFNews/featured" target="_blank"
                                            class="social-item"><span><i class="fa-brands fa-youtube"></i></span></a>
                                        <a href="https://whatsapp.com/channel/0029VajdZqv9xVJbRYtSFM3C"
                                            target="_blank" class="social-item"><span><i
                                                    class="fa-brands fa-whatsapp"></i></span></a>
                                    </div>
                                    {{-- login button here --}}
                                   {{-- login button here --}}
                                    @auth('viewer')
                                        <form method="POST" action="{{ rtrim(config('global.base_url'), '/').(route('viewer.logout', [], false)) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="log logout-btn">
                                                <i class="fas fa-sign-out-alt"></i>
                                                Logout
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ rtrim(config('global.base_url'), '/').(route('auth.google', [], false)) }}" class="log">
                                            <i class="fa-solid fa-user"></i>
                                            Login
                                        </a>
                                    @endauth
                                </div>
                                <!-- Search Modal -->
                                <div id="searchModal" class="search-modal">
                                    <div class="search-modal-content">
                                        <span class="closeBtn" onclick="closeSearchModal()">&times;</span>
                                        <form method="get" action="{{ asset('/search') }}">
                                            <input type="search" name="search" placeholder="खोजें..."
                                                class="search-input" required>
                                            <button class="srch-btn" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                                <small class="Headertag m-0 htag" style="margin-left: 0px;white-space: nowrap;"> <span
                                        class="" style="color: #fff;">जिस पर देश</span><span
                                        class="HeadertagHalf">करता है भरोसा</span> </small>
                            </div>
                            <div class="modal-overlay" id="modal-overlay">
                                <!-- Modal Content -->
                                <div class="modal-content">
                                    <div class="modal_top">
                                        <button class="close_btn" id="close-btn">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                        <!-- NL1025:20Sept:2025:Added config path -->
                                        <a href="{{ asset('/') }}" class="modal_logo"><img loading="lazy"
                                               src="{{config('global.base_url_frontend')}}frontend/images/logo.png" alt=""></a>
                                        <a class="Headertag ms-0" style="margin-left: 0px"> <span class="">जिस
                                                पर
                                                देश</span><span class="HeadertagHalf">करता है भरोसा</span> </a>
                                    </div>

                                    <?php
                                    // Define category-to-icon mapping
                                    $categoryIcons = [
                                        'न्यूज' => 'fa-solid fa-newspaper',
                                        'राज्य' => 'fa-solid fa-landmark',
                                        'एक्सक्लूसिव' => 'fa-solid fa-star',
                                        'खेल' => 'fa-solid fa-futbol',
                                        'मनोरंजन' => 'fa-solid fa-film',
                                        'धर्म ज्ञान' => 'fa-solid fa-om',
                                        'टेक्नोलॉजी' => 'fa-solid fa-microchip',
                                        'लाइफस्टाइल' => 'fa-solid fa-heart',
                                        'पॉडकास्ट' => 'fa-solid fa-podcast',
                                        'दुनिया' => 'fa-solid fa-globe',
                                        'विधान सभा चुनाव' => 'fa-solid fa-vote-yea',
                                    ];
                                    //$toggleMenus = App\Models\Menu::where('menu_id', 0)->where('status', 1)->where('type_id', '1')->where('category_id', '2')->get();
                                    //$toggleMenus = App\Models\Menu::where('menu_id', 0)->get();
                                    $toggleMenus = App\Models\Menu::whereRelation('type', 'type', 'Header')
                                        ->whereRelation('category', 'category', 'User')
                                        ->where([['status', '1'], ['menu_id', 0]])
                                        ->whereNotNull('sequence_id')
                                        ->where('sequence_id', '!=', 0)
                                        ->orderBy('sequence_id', 'asc')
                                        ->get();
                                    ?>
                                    <ul class="modalmenu">
                                        @foreach ($toggleMenus as $menu)
                                            <?php
                                            $subMenus = App\Models\Menu::where('menu_id', $menu->id)->where('status', 1)->where('type_id', 1)->where('category_id', 2)->orderBy('sequence_id', 'asc')->get();
                                            ?>
                                            <li class="modal_item">
                                                <a href="{{ asset($menu->menu_link) }}">
                                                    <i
                                                        class="{{ $categoryIcons[$menu->menu_name] ?? 'fa-solid fa-link' }}"></i>
                                                    {{ $menu->menu_name }}
                                                    @if (count($subMenus) > 0)
                                                        <i class="fa-solid fa-chevron-down submenu-toggle-icon"></i>
                                                    @endif
                                                </a>

                                                @if (count($subMenus) > 0)
                                                    <ul class="modal_submenu">
                                                        @foreach ($subMenus as $subMenu)
                                                            <li>
                                                                <a href="{{ asset($subMenu->menu_link) }}">
                                                                    <i class="fas fa-circle"
                                                                        style="font-size: 0.5em; vertical-align: middle; margin-right: 8px;"></i>
                                                                    {{ $subMenu->menu_name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="--hdr-bottom">

                                <nav class="main-navigation scrollMargin" id="myHeader">
                                    <div id="" class="inner-nav cm-container">
                                        <ul class="menuSearch menuSearchalign-end navmenu">

                                            <li id="navLogo"
                                                class="menu-item menu-item-type-custom menu-item-object-custom">
                                                <a class="sub_logo" href="{{ asset('/') }}"
                                                    aria-current="page">
                                                    <!-- NL1025:20Sept:2025:Added config path -->
                                                    <img loading="lazy"
                                                       src="{{config('global.base_url_frontend')}}frontend/images/logo.png"
                                                        alt="" style="width: 41px" />
                                                </a>
                                            </li>

                                            <?php
                                            ?>
                                            @foreach ($menus as $menu)
                                                <?php
                                                $subMenus = App\Models\Menu::where('menu_id', $menu['id'])->where('status', '1')->where('type_id', '1')->where('category_id', '2')->get();
                                                $file = App\Models\File::where('id', $menu['image'])->first();
                                                ?>
                                                <li class="item {{ Str::contains($menu['menu_link'], 'state-legislative-assembly-election') ? 'new' : '' }}">

                                                    <a href="{{ asset($menu['menu_link']) }}" class="link">
                                                        <span> {{ $menu['menu_name'] }}</span>
                                                        @if (count($subMenus) > 0)
                                                            <svg viewBox="0 0 360 360" xml:space="preserve">
                                                                <g id="SVGRepo_iconCarrier">
                                                                    <path id="XMLID_225_"
                                                                        d="M325.607,79.393c-5.857-5.857-15.355-5.858-21.213,0.001l-139.39,139.393L25.607,79.393 c-5.857-5.857-15.355-5.858-21.213,0.001c-5.858,5.858-5.858,15.355,0,21.213l150.004,150c2.813,2.813,6.628,4.393,10.606,4.393 s7.794-1.581,10.606-4.394l149.996-150C331.465,94.749,331.465,85.251,325.607,79.393z">
                                                                    </path>
                                                                </g>
                                                            </svg>
                                                        @endif
                                                    </a>

                                                    @if (count($subMenus) > 0)
                                                        <div class="submenu">
                                                            @foreach ($subMenus as $subMenu)
                                                                <div class="submenu-item">
                                                                    <a href="{{ asset($subMenu['menu_link']) }}"
                                                                        class="submenu-link">
                                                                        {{ $subMenu['menu_name'] }} </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
        </header>
        <nav class="main-navigation-mob" id="mainMenu">
            <div class="menu-container">
                <ul class="menu-list">
                    @php
                      $baseUrl = config('global.base_url'); // your configured base URL
                       
                    @endphp

                     @foreach ($menus  as $item)
		               @php
                        if (substr($item['menu_link'], 0, 1) !== '/') {
                            $item['menu_link'] = '/' . $item['menu_link'];
                        }
                           $fullUrl = (substr($baseUrl, -1) === '/' ? substr($baseUrl, 0, -1) : $baseUrl) . $item['menu_link'];
                        @endphp   
                     <li
                            class="menu-item {{ request()->is('/') &&  $item['menu_link'] === '/' ? 'active' : '' }}{{ request()->is(ltrim($item['menu_link'], '/')) && $item['menu_link'] !== '/' ? 'active' : '' }}">
                            <a href="{{ $fullUrl}}" class="menu-link {{   str_contains( $item['menu_link'], 'state-legislative-assembly-election') ? 'mobile-new' : '' }}">{{ $item['menu_name'] }}
</a>
                        </li>
                    @endforeach
                   

                </ul>
            </div>
        </nav>

        <div id="content" class="site-content">
            @yield('content')
        </div>
        <div class="mobile-sticky-ad" id="stickyFooterAd">

            <div class="sticky-close-btn" onclick="document.getElementById('stickyFooterAd').style.display='none';">
                <i class="fa-solid fa-xmark"></i> Close
            </div>

            <span
                style="font-size: 9px; color: #999; margin-top:0px; margin-bottom: 0px; display:block; text-align:center;">ADVERTISEMENT</span>

            <div
                style="position: relative; min-width: 320px; min-height: 50px; display: flex; justify-content: center;">

                <ins class="adsbygoogle" style="display:inline-block;width:320px;height:50px"
                    data-ad-client="ca-pub-3986924419662120" data-ad-slot="6911924096"></ins>

                <a href="https://www.newsnmf.com/nmfapps/" target="_blank" class="ad-fallback-content"
                    id="myFallback">
                    <div class="dl-wrapper">
                        <div class="ad-fallback-info">
                            <div class="ad-app-icon">
                                <img src="https://www.newsnmf.com/frontend/images/logo.png" alt="NewsNMF"
                                    class="--logo-sm" />
                            </div>
                            <div class="ad-text-group">
                                <div class="ad-app-name">NMF App</div>
                            </div>
                        </div>
                        <div class="ad-fallback-btn">Download</div>
                    </div>
                </a>

            </div>

            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});

                window.addEventListener('load', function() {
                    setTimeout(function() {
                        var adSlot = document.querySelector('#stickyFooterAd ins.adsbygoogle');
                        // If AdSense is empty or hidden, show Fallback
                        if (adSlot && (adSlot.getAttribute('data-ad-status') === 'unfilled' || adSlot
                                .offsetHeight === 0 || adSlot.style.display === 'none')) {
                            if (adSlot) adSlot.style.display = 'none';
                            document.getElementById('myFallback').style.display = 'block';
                        }
                    }, 2500);
                });
            </script>
        </div>
        <!-- Bottom Navigation -->
         <?php
           use App\Models\Clip;
          
            $clip = Clip::with('category')
            ->where('status', 1)
            ->latest('id')
            ->first();
            if($clip){
                $catUrl = optional($clip->category)->site_url;
            }
            $videourl=$clip->site_url;
         ?>
        <div class="btm-nav" id="btm-nav">
                <a href="{{ config('global.base_url') . 'short-videos/' . $catUrl . '/' . $videourl }}" class="nav-item">
                <i class="fas fa-bolt"></i>
                <span>शॉर्ट्स</span>
            </a>
            <a href="{{ config('global.base_url'). 'web-stories' }}" class="nav-item">
                <div class="webstory-icon"></div>  
                <span>वेब स्टोरीज़</span>
            </a>
            <a href="{{config('global.base_url') }}" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>होम</span>
            </a>
            <a href="{{ config('global.base_url') . 'nmfvideos' }}" class="nav-item">
                <i class="fa-solid fa-video"></i> 
                <span>वीडियो</span>
            </a>
            <a href="javascript:void(0);" class="nav-item" onclick="openSearchModal()">
                <i class="fas fa-search"></i>
                <span>खोजें</span>
            </a>
        </div>
        <script>
            // Buttom nav hide on scroll
        let lastScroll = 0;
        const stickyAd = document.getElementById("stickyFooterAd");
        const bottomNav = document.getElementById("btm-nav");

        window.addEventListener("scroll", function () {
        const currentScroll = window.pageYOffset;

        if (currentScroll > lastScroll + 10) {
        stickyAd.style.transform = "translateY(0)";      // show smooth
        bottomNav.style.transform = "translateY(100%)";  // hide smooth
        } 
        else if (currentScroll < lastScroll - 10) {
        stickyAd.style.transform = "translateY(100%)";   // hide smooth
        bottomNav.style.transform = "translateY(0)";     // show smooth
        }

        lastScroll = currentScroll;
        });
        </script>


        <footer class="footer_main">
            <div class="cm-container">
                <div class="footer-top">
                    <div class="footer_left">
                        <div class="footer_logo_wrap">
                            <a href="{{ asset('/') }}" class="footer_logo">
                                <!-- NL1025:20Sept:2025:Added config path -->

                                <img loading="lazy" src="{{config('global.base_url_frontend')}}frontend/images/logo.png" alt="" />
                            </a>
                            <div class="footer_logo">
                                <img loading="lazy" src="{{config('global.base_url_asset')}}asset/images/kmc_logo.png" alt="">
                            </div>
                        </div>
                        <p>NMF News is a Subsidary of Khetan Media Creation Pvt Ltd</p>
                        <div class="contact_wrap">
                            <div class="contact_block">
                                <div class="ct_left">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="ct_right">
                                    <small>Give us a Call</small>
                                    <a href="tel:+91-080767 27261">+91-080767 27261</a>
                                </div>
                            </div>
                            <div class="contact_block">
                                <div class="ct_left">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="ct_right">
                                    <small>Visit Our Office</small>
                                    <p>D-4 1st Floor, Sector 10, Noida, Uttar Pradesh 201301</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer_centre">
                        <div class="footer_col">
                            <h4>Company</h4>
                            <ul class="footer_menu">
                                <li class="footer_item"><a href="{{ asset('/about') }}">About us</a></li>
                                <li class="footer_item"><a href="{{ asset('/privacy') }}">Privacy Policy</a></li>
                                <li class="footer_item"><a href="{{ asset('/disclaimer') }}">Disclaimer</a></li>
                                <li class="footer_item"><a href="{{ asset('/contact') }}">Contact</a></li>
                            </ul>
                        </div>
                        <div class="footer_col">
                            <h4>Category</h4>
                            <?php
                            $footer_menus = App\Models\Menu::where('menu_id', 0)->where('status', 1)->where('type_id', '1')->where('category_id', '2')->limit(8)->get();
                            $chunks = $footer_menus->chunk(4);
                            ?>
                            <ul class="footer_menu">
                                @foreach ($chunks as $chunk)
                                    <div class="footer_ct">
                                        @foreach ($chunk as $footer_menu)
                                            <li class="footer_item">
                                                <a
                                                    href="{{ $footer_menu->menu_link }}">{{ $footer_menu->menu_name }}</a>
                                            </li>
                                        @endforeach
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="footer_right">
                        <h5>Download App</h5>
                        <div class="app_btn_wrap">
                            <a href="https://play.google.com/store/apps/details?id=com.kmcliv.nmfnews"
                                class="playstore-button">
                                <svg viewBox="0 0 512 512" class="_icon" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M99.617 8.057a50.191 50.191 0 00-38.815-6.713l230.932 230.933 74.846-74.846L99.617 8.057zM32.139 20.116c-6.441 8.563-10.148 19.077-10.148 30.199v411.358c0 11.123 3.708 21.636 10.148 30.199l235.877-235.877L32.139 20.116zM464.261 212.087l-67.266-37.637-81.544 81.544 81.548 81.548 67.273-37.64c16.117-9.03 25.738-25.442 25.738-43.908s-9.621-34.877-25.749-43.907zM291.733 279.711L60.815 510.629c3.786.891 7.639 1.371 11.492 1.371a50.275 50.275 0 0027.31-8.07l266.965-149.372-74.849-74.847z">
                                    </path>
                                </svg>
                                <span class="texts">
                                    <span class="text-1">GET IT ON</span>
                                    <span class="text-2">Google Play</span>
                                </span>
                            </a>
                            <a href="https://apps.apple.com/us/app/nmf-news/id6745018964" class="playstore-button">
                                <svg viewBox="0 0 512 512" class="_icon" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg" style="margin-right: -7px;">
                                    <path
                                        d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z" />
                                </svg>
                                <span class="texts">
                                    <span class="text-1">GET IT ON</span>
                                    <span class="text-2">App Store</span>
                                </span>
                            </a>
                        </div>
                        <div id="footer-socials">
                            <p>Follow us</p>
                            <div class="socials inline-inside socials-colored">
                                <a href="https://{{ isset($setting->facebook) ? $setting->facebook : '' }}"
                                    target="_blank" title="Facebook" class="socials-item">
                                    <i class="fab fa-facebook-f facebook"></i>
                                </a>
                                <a href="https://{{ isset($setting->twitterx) ? $setting->twitterx : '' }}"
                                    target="_blank" title="Twitter" class="socials-item">
                                    <i class="fab fa-twitter twitter"></i>
                                </a>
                                <a href="https://{{ isset($setting->instagram) ? $setting->instagram : '' }}"
                                    target="_blank" title="Instagram" class="socials-item">
                                    <i class="fab fa-instagram instagram"></i>
                                </a>
                                <a href="https://{{ isset($setting->youtube) ? $setting->youtube : '' }}"
                                    target="_blank" title="YouTube" class="socials-item">
                                    <i class="fab fa-youtube youtube"></i>
                                </a>
                                <a href="https://{{ isset($setting->whatsapp) ? $setting->whatsapp : '' }}"
                                    target="_blank" title="WhatsApp" class="socials-item">
                                    <i class="fab fa-whatsapp whatsapp"></i>
                                </a>
                            </div>
                        </div>
                        @if (session('subscribemessage'))
                            <div class="alert alert-success mt-1">
                                {{ session('subscribemessage') }}
                            </div>
                        @endif

                        <form method="POST" action="{{config('global.base_url') }}">
                            @csrf
                            <input type="hidden" name="_action" value="subscribe">
                            <div class="nsl-block">
                                <p>Stay Informed. Get Notified</p>
                                <div class="input_wrap">
                                    <input placeholder="Your email address" class="signup_input" name="email"
                                        type="email" required>
                                    <button class="Subscribe-btn">Subscribe</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="cm-container row">
                    <div class="col-md-6 ps-0">
                        <div class="footer-site-info">Copyright © 2025 KMC PVT. LTD. All Rights Reserved.</div>
                    </div>
                    <div class="ftcol">
                        <div class="poweredby">
                            <span>Designed & Developed by</span>
                            <!-- NL1025:20Sept:2025:Added config path -->

                            <a href="https://www.abrosys.com/"> <img width="102" height="19"
                                    src="{{config('global.base_url_asset')}}asset/images/abrosys.png"
                                    alt="Abrosys Technologies Private Limited"></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="backtoptop">
            <button id="toTop" class="btn btn-info">
                <i class="fa fa-angle-up" aria-hidden="true"></i>
            </button>
        </div>
	  <!-- NL1023 : 16/09/2023 - commented wp-script -->
        {{-- <script type="text/javascript" id="cream-magazine-bundle-js-extra">
            var cream_magazine_script_obj = {
                "show_search_icon": "1",
                "show_news_ticker": "1",
                "show_banner_slider": "1",
                "show_to_top_btn": "1",
                "enable_sticky_sidebar": "1",
                "enable_sticky_menu_section": ""
            };
        </script> --}}
        <script type="text/javascript" src="{{config('global.base_url_asset')}}asset/js/swiper-bundle.min.js" ></script>
        <script type="text/javascript" src="{{ asset('/asset/js/bundle.min.js') }}" id="cream-magazine-bundle-js" defer></script>
        <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v55bfa2fee65d44688e90c00735ed189a1713218998793"
            integrity="sha512-FIKRFRxgD20moAo96hkZQy/5QojZDAbyx0mQ17jEGHCJc/vi0G2HXLtofwD7Q3NmivvP9at5EVgbRqOaOQb+Rg=="
            data-cf-beacon='{"rayId":"877e2b567a269fa5","r":1,"version":"2024.3.0","token":"e07ffd4cc02748408b326adb64b6cc16"}'
            crossorigin="anonymous"></script>
        <script src="{{ asset('asset/js/main.js') }}" defer></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".modal_item > a").forEach(function(menuLink) {
                    menuLink.addEventListener("click", function(e) {
                        const parent = menuLink.parentElement;
                        const submenu = parent.querySelector(".modal_submenu");

                        if (submenu) {
                            e.preventDefault();
                            parent.classList.toggle("open");
                        }
                    });
                });
            });
        </script>
        <script>
            $(function() {
                $('#load-more-btn').on('click', function() {
                    let button = $(this);
                    let offset = parseInt(button.data('offset'));
                    let name = button.data('name');
                    let state = button.data('state');
                    let subcat = button.data('subcat');

                    button.prop('disabled', true).html(
                        'Loading... <i class="fa-solid fa-spinner fa-spin"></i>');


                    $.ajax({
                        url: `/categories/${name}/load-more`,
                        method: 'GET',
                        data: {
                            offset,
                            state,
                            subcat
                        },
                        success: function(res) {
                            if (res.count > 0) {
                                $('#blog-list').append(res.blogs);
                                button.data('offset', offset + res.count)
                                    .prop('disabled', false)
                                    .html('Show More <i class="fa-solid fa-angle-down"></i>');
                            } else {
                                button.remove(); // no more blogs
                            }
                        },
                        error: function() {
                            alert('Failed to load more blogs.');
                            button.prop('disabled', false)
                                .html('Show More <i class="fa-solid fa-angle-down"></i>');
                        }
                    });
                });
            });
        </script>
        <script>
            $(function() {
                $('#state-load-more-btn').on('click', function() {
                    let button = $(this);
                    let offset = parseInt(button.data('offset'));
                    let name = button.data('name');

                    button.prop('disabled', true).html(
                        'Loading... <i class="fa-solid fa-spinner fa-spin"></i>');

                    $.ajax({
                        url: `/state/${name}/load-more`,
                        method: 'GET',
                        data: { offset },
                        success: function(res) {
                            if (res.count > 0) {
                                $('#blog-list').append(res.blogs);
                                button.data('offset', offset + res.count)
                                    .prop('disabled', false)
                                    .html('Show More <i class="fa-solid fa-angle-down"></i>');
                            } else {
                                button.remove();
                            }
                        },
                        error: function() {
                            alert('Failed to load more blogs.');
                            button.prop('disabled', false).html('Show More <i class="fa-solid fa-angle-down"></i>');
                        }
                    });
                });
            });
        </script>
 <script>
$(document).ready(function(){
    $(".middle_widget_six_carousel").owlCarousel({
        items: 2,     
        margin: 15,    
        loop: true,     
        autoplay: true, 
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        nav: true,       
        dots: false,       
        responsive: {
            0:   { items: 1 },
            600: { items: 2 },
            1000:{ items: 2 }
        }
    });
});
</script>
 
 
        <x-home.gdpr-consent />
</body>
</html>
