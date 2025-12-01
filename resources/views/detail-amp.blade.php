<!doctype html>
<html ⚡ lang="en">

<head>
    @php
        $ff = config('global.blog_images_everywhere')($data['blog'] ?? null);
        $imageToUse = asset($ff);
        $customImageUrl = asset('asset/images/NMF_BreakingNews.png');
    @endphp
    @php
        $canonicalUrl = str_replace('/amp', '/', url()->current());
    @endphp
    <meta charset="utf-8">
    <title>{{ $data['blog']->name }}</title>
    <meta name="description"
        content="{{ Str::limit(strip_tags($data['blog']->sort_description ?? $data['blog']->description), 160) }}">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <link rel="canonical" href="{{ $canonicalUrl }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-font" src="https://cdn.ampproject.org/v0/amp-font-0.1.js"></script>
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
    <script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>
    <script async custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"></script>
    <script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>
    <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
    @yield('head')
    <style amp-custom>
        .news-tabs {
            font-size: 20px;
            margin-top: 0;
            padding-left: 10px;
            border-left: 4px solid #ff0000;
            color: #1a1a1a;
            font-weight: 700;
            margin-bottom: 15px;
            border-radius: 3px;
            display: block;
        }

        .news-tabs a {
            color: #1a1a1a;
            text-decoration: none;
        }


        /* ---------------------------
       categories-canoon
       --------------------------- */
        #categories-canoon {
            background-color: #fff;
            margin-bottom: 15px;
            padding: 0 0 10px;
            border-radius: 0;
            border-width: 1px;
            border-style: solid;
            border-color: #fff #fff #c9c9c9;
            border-bottom: 1px solid #c9c9c9;
        }

        #categories-canoon:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }


        /* ---------------------------
       MORE BUTTON
       --------------------------- */
        .more_btn {
            display: inline-block;
            color: #f3242b;
            font-size: 15px;
            font-weight: 600;
            line-height: 17px;
            border-radius: 13px;
            padding: 3px 12px;
            text-decoration: none;
            background-color: transparent;
            margin-top: 12px;
            margin-bottom: 12px;
        }

        /* Base Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Devanagari font */
        body {
            font-family: "Noto Sans Devanagari", "Nirmala UI", "Mangal", "Utsaah",
                -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .noto-sans-loaded body {
            font-family: "Noto Sans Devanagari", "Nirmala UI", "Mangal", "Utsaah",
                -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .ad-container {
            max-width: 400px;
            margin: 0 auto;
        }

        /* Loading state */
        .noto-sans-loading {
            visibility: hidden;
        }

        /* Container */
        .article--container {
            margin: 0 auto;
        }

        .article--main-content {
            padding: 0 15px;
        }

        .cm-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Read more functionality */
        .readmore {
            position: relative;
            max-height: 680px;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .readmore.expanded {
            max-height: none;
            overflow: visible;
        }

        .readmore__fade {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 84px;
            background: linear-gradient(transparent, #ffffff);
            pointer-events: none;
        }

        .readmore__actions {
            text-align: center;
            margin-top: 10px;
        }

        .readmore__btn {
            cursor: pointer;
            padding: 11px 17px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: #282828;
            display: inline-block;
            color: #ffffff;
        }

        .readmore__btn[hidden] {
            display: none;
        }

        .readmore__fade[hidden] {
            display: none;
        }

        /* optional: smaller fold on tiny screens */
        @media (max-width: 480px) {
            .readmore {
                max-height: 540px;
            }
        }

        /* Ad Container */
        .ad-container {
            text-align: center;
            margin: 20px auto;
            max-width: 300px;
        }


        .ad-title {
            font-size: 13px;
            color: #666;
        }


        /* Ensure ads don't break layout */
        amp-ad {
            display: block;
        }

        /* Related news */
        .rel_heading {
            font-size: 20px;
            margin-top: 0;
            padding-left: 10px;
            border-left: 4px solid #ff0000;
            color: #1a1a1a;
            font-weight: 700;
            margin-bottom: 15px;
            border-radius: 3px;
            display: block;
        }

        .rel_article {
            display: flex;
            background: transparent;
            overflow: hidden;
            transition: transform 0.3s ease;
            margin-bottom: 4px;
        }

        .rel_top {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 8px;
            width: 125px;
            flex-shrink: 0;
            height: 70px;
            margin-right: 8px;
        }

        .rel_top img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .rel_top .nws_article_strip {
            display: none;
        }

        .at_content>div {
            color: #333333;
            line-height: 30px;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .rel_bottom a {
            font-size: 15px;
            line-height: 22px;
            font-weight: 500;
            text-align: left;
            margin-bottom: 0;
            text-overflow: ellipsis;
            overflow: hidden;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            display: -webkit-box;
            color: #333;
            text-decoration: none;
            font-weight: 600;
        }

        /* Breadcrumb */
        .article-breadcrumb {
            margin-top: 82px;
            padding: 5px 0px;
            border-radius: 8px;
            font-family: "Noto Sans Devanagari", -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, sans-serif;
        }

        .breadcrumb-list {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin: 8px 15px;
            padding: 0;
            align-items: center;
        }

        .breadcrumb-item svg {
            margin-top: 3px;
        }

        .breadcrumb-item a {
            color: #666;
            font-size: 14px;
            text-decoration: none;
        }

        .breadcrumb-item.current {
            color: #333;
            font-weight: bold;
        }

        /* Header */
        .article--header {
            margin-bottom: 5px;
        }

        .article--title {
            font-size: 22px;
            line-height: 30px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .article--subtitle {
            font-size: 18px;
            color: #262525;
            padding-right: 0;
            line-height: 28px;
            font-weight: 400;
            margin-bottom: 6px;
        }

        /* Meta Information */
        .article--meta {
            display: flex;
            flex-wrap: wrap;
            gap: 5px 12px;
            margin-bottom: 15px;
        }

        .article--meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #666;
        }

        .article--meta-item .l1 {
            color: #ff0000;
            text-decoration: none;
        }

        .article--meta-item .l2 {
            color: #333;
        }

        .article--meta-item a:hover {
            text-decoration: underline;
        }

        /* Action Bar */
        .article--actions {
            display: flex;
            justify-content: left;
            align-items: center;
            margin-bottom: 15px;
        }

        .article--share-container {
            position: relative;
        }

        .article--share-button svg {
            margin-bottom: -3px;
            margin-right: 4px;
        }

        .article--share-button {
            cursor: pointer;
            padding: 8px 5px 12px;
            line-height: 0px;
            font-size: 15px;
            width: 6.3em;
            color: white;
            background: #212121;
            background-size: cover;
            background-blend-mode: overlay;
            border-radius: 0.5em;
            outline: 0.1em solid #353535;
            border: 0;
            transition: all 0.3s ease-in-out;
            position: relative;
            margin-right: 15px;
        }

        .article--share-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: #ffffffd1;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 3px 20px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 2;
            margin-top: 5px;
        }

        .article--social-links {
            display: flex;
            gap: 10px;
            list-style: none;

        }

        .article--social-link a {
            display: block;
            padding: 8px;
            border-radius: 4px;
            transition: background 0.3s;
            color: #000;
        }

        .article--social-link a:hover {
            background: #f8f9fa;
        }

        /* Media Content */
        .article--media {
            margin-bottom: 15px;
            border-radius: 12px;
            overflow: hidden;
        }

        .article--image-wrapper {
            position: relative;
        }

        .article--image-credit {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
            color: white;
            padding: 15px;
            font-size: 12px;
        }

        /* Follow us */
        .follow_us {
            display: flex;
            justify-content: center;
            flex-direction: row;
            align-items: center;
            position: relative;
            gap: 15px;
            margin-bottom: 10px;
        }

        .follow_us_socials .socials-item:last-of-type {
            margin-right: 0px;
        }

        .follow_us h6 {
            font-size: 13px;
            margin-bottom: 0px;
            margin-right: 10px;
            color: #6b6a6a;
            white-space: nowrap;
        }

        .flw_wrap {
            display: flex;
            align-items: center;
        }

        .follow_us_bar {
            width: 100%;
            height: 1px;
            background: #d5d5d5;
        }

        .follow_us_socials {
            display: flex;
        }

        .follow_us_socials .socials-item {
            display: inline-block;
            vertical-align: top;
            text-align: center;
            transition: all 0.3s;
            margin: 0 6px;
            line-height: 16px;
            border: none;
            padding: 10px;
            border-radius: 9px;
            background-color: #ffffff;
            box-shadow: 3px 9px 16px rgb(0 0 0 / 13%), -3px -3px 10px rgb(255 255 255 / 0%), inset 14px 14px 26px rgb(182 182 182 / 30%), inset -3px -3px 15px rgba(255, 255, 255, 0.05);
        }

        .follow_us_socials .socials-item:hover {
            box-shadow: 0 0px 14px rgb(255, 176, 176);
        }

        .follow_us_socials .socials-item .facebook {
            color: #1877f2;
        }

        .follow_us_socials .socials-item .fa-x-twitter {
            color: #333;
        }

        .follow_us_socials .socials-item .instagram {
            color: #e4405f;
        }

        .follow_us_socials .socials-item .youtube {
            color: #ff0000;
        }

        .follow_us_socials .socials-item .whatsapp {
            color: #25d366;
        }

        /* WhatsApp button */
        .wp-btn {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .article--whatsapp-button svg {
            height: 29px;
            position: absolute;
            padding: 0px 7px;
            margin-top: -1px;
        }

        .article--whatsapp-button {
            margin-top: 15px;
            display: inline-block;
            transition: all 0.2s ease-in;
            position: relative;
            overflow: hidden;
            z-index: 1;
            color: #ffffff;
            padding: 0.6em 3em 0.7em 1.2em;
            font-size: 18px;
            border-radius: 0.5em;
            border: #009087;
            background-color: #009087;
            box-shadow: 6px 6px 12px #c5c5c5, -6px -6px 12px #ffffff;
            text-decoration: none;
            font-weight: 600;
        }

        .article--whatsapp-button:hover {
            color: #ffffff;
            background-color: #028a81;
        }

        /* Article Content */
        .article--content {
            color: #242424;
            line-height: 30px;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .article--content p {
            color: #2f2e2e;
            line-height: 30px;
            margin-bottom: 10px;
            font-size: 19px;
            font-weight: 400;
        }

        .article--content p span {
            color: inherit;
            font-size: inherit;
            font-weight: 400;
            font-size: 19px;
        }

        .article--content h2,
        .article--content h3 {
            margin: 30px 0 15px;
            color: #1a1a1a;
            font-size: 20px;
            line-height: 28px;
        }

        .article--content amp-youtube,
        .article--content amp-video,
        .article--content amp-img,
        .article--content img,
        .article--content video,
        .article--content iframe {
            max-width: 100%;
            height: auto;
            display: block;
            width: 100%;
        }

        /* Tags */
        .article--tags-title {
            font-size: 20px;
            margin-top: 0;
            padding-left: 10px;
            border-left: 4px solid #ff0000;
            color: #1a1a1a;
            font-weight: 700;
            margin-bottom: 15px;
            border-radius: 3px;
            display: block;
        }

        .article--tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .article--tag {
            background: #f1f4f7;
            color: #5a5c60;
            padding: 4px 14px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .article--tag:hover {
            background: #007bff;
            color: white;
        }

        /* Ads */
        .article--ad-horizontal {
            text-align: center;
            margin-bottom: 15px;
        }

        /* Utility Classes */
        .article--mb-20 {
            margin-bottom: 20px;
        }

        .article--mb-30 {
            margin-bottom: 30px;
        }

        .article--mt-30 {
            margin-top: 30px;
        }

        .article--hidden {
            display: none;
        }

        /* Header Mobile */
        .--header-amp {
            background: #000;
            padding: 6px 15px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
        }

        .--header-container {
            min-height: 100%;
            display: flex;
            align-items: center;
        }

        .--header-left {
            padding-right: 0;
            height: 100%;
            display: flex;
            align-items: center;
            border: none;
        }

        .--nmf-logo-amp {
            width: 44px;
            height: 44px;
            overflow: hidden;
        }

        .--nmf-logo-amp img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .--header-right {
            width: 100%;
            height: 100%;
            border: none;
        }

        .--hdr-top {
            min-height: 100%;
            border: none;
            padding-left: 15px;
            padding-right: 0;
            display: flex;
            flex-direction: row-reverse;
            align-items: center;
            justify-content: space-between;
        }

        .--hdr-t-l {
            display: flex;
            align-items: center;
            flex-direction: row-reverse;
            gap: 12px;
            width: fit-content;
        }

        .--toggle-box {
            border: none;
            background: transparent;
            outline: none;
            padding: 0;
        }

        .burger {
            position: relative;
            width: 32px;
            height: 20px;
            background: transparent;
            cursor: pointer;
            display: block;
            margin: 0;
        }

        .burger span {
            display: block;
            position: absolute;
            height: 1.5px;
            width: 100%;
            background: #d4d4d4;
            border-radius: 9px;
            opacity: 1;
            left: 0;
            transform: rotate(0deg);
            transition: .25s ease-in-out;
        }

        .burger span:nth-of-type(1) {
            top: 0px;
        }

        .burger span:nth-of-type(2) {
            top: 50%;
            transform: translateY(-50%);
        }

        .burger span:nth-of-type(3) {
            top: 100%;
            transform: translateY(-100%);
        }

        .Headertag {
            color: #fff;
            white-space: nowrap;
            display: block;
        }

        .HeadertagHalf {
            margin-left: 5px;
            font-weight: 700;
            color: red;
        }

        /* AMP Lightbox Base */
        amp-lightbox {
            /* Your existing base style, no change here */
            background: rgba(0, 0, 0, 0.5);
        }

        .modalmenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .modal_item {
            margin: 0;
            padding: 5px 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        /* Target direct links (items without submenus) */
        .modal_item>a {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: #333;
            font-size: 1rem;
        }

        .modal_item>a:hover {
            background-color: #f8f8f8;
        }

        .modal_item i {
            margin-right: 10px;
            width: 20px;
            /* Standardize icon width */
            text-align: center;
            color: #999;
        }

        /* ACCORDION CONTAINER (Removing default AMP borders/backgrounds) */
        .modal_item amp-accordion {
            border: none;
        }

        .modal_item amp-accordion section {
            margin: 0;
            border: none;
            background-color: transparent;
        }

        .amp-menu-header {
            margin: 0;
            padding: 0;
            cursor: pointer;
            background-color: transparent;
            font-size: 1rem;
            font-weight: normal;
            color: #333;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            line-height: 1.2;
            border: none;
        }

        .amp-menu-header:hover {
            background-color: #f8f8f8;
        }

        .amp-menu-header::after {
            content: '\25bc';
            font-size: 0.7em;
            margin-right: 5px;
            transition: transform 0.3s;
            transform-origin: center;
            position: absolute;
            right: 10px;
            color: #666;
        }

        .modal_item amp-accordion section[expanded] .amp-menu-header::after {
            transform: rotate(180deg);
        }


        .amp-menu-submenu-content {
            padding: 0;
            background-color: #fcfcfc;
        }

        .amp-menu-parent-link-in-dropdown {
            display: block;
            padding: 10px 15px;
            font-weight: 500;
            text-decoration: none;
            color: #007bff;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
        }


        .modal_submenu {
            list-style: none;
            padding: 5px 0 5px 0;
            margin: 0;
        }

        .modal_submenu li a {
            display: block;
            padding: 8px 15px 8px 35px;
            text-decoration: none;
            color: #666;
            font-size: 0.9rem;
        }

        .modal_submenu li a:hover {
            background-color: #f0f0f0;
        }


        /* Modal Content - The Slide-In Menu */
        .modal-content {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            box-shadow: 0 7px 29px rgba(0, 0, 0, 0.2);
            padding: 20px 0;
            z-index: 10001;

            transition: transform 0.6s cubic-bezier(0.68, 0.55, 0.265, 0.75);
            overflow-y: auto;
        }

        .modal_top {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-bottom: 23px;
            position: relative;
        }

        .close_btn {
            position: absolute;
            top: 12px;
            right: 16px;
            background: #fff;
            font-size: 20px;
            line-height: 38px;
            width: 38px;
            height: 38px;
            cursor: pointer;
            color: #ff5050;
            border-radius: 50%;
            border: 1px solid #cbcbcb;
            transition: transform 0.3s ease;
            text-align: center;
        }

        .close_btn:hover {
            transform: rotate(180deg);
        }

        .modal_logo {
            width: 62px;
        }

        .modal_logo img {
            width: 100%;
            height: auto;
        }



        .modalmenu a {
            font-size: 17px;
            color: #3e3e3e;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .modalmenu a i {
            margin-right: 10px;
        }

        .modalmenu a:hover {
            color: #ff0000;
        }


        /* Mobile Navigation */
        .main-navigation-mob {
            position: fixed;
            width: 100%;
            top: 53px;
            display: block;
            overflow-x: auto;
            z-index: 19;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid #eee;
        }

        .menu-container {
            overflow-x: auto;
            padding: 0 12px;
        }

        .menu-list {
            display: flex;
            gap: 20px;
            padding: 7px 0 0px;
            margin: 0;
            list-style: none;
            white-space: nowrap;
        }

        .menu-item {
            position: relative;
            font-size: 15px;
            font-weight: 500;
            padding-bottom: 6px;
            cursor: pointer;
        }

        .menu-item .menu-link {
            white-space: nowrap;
            color: #000;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .menu-item.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            height: 4px;
            width: 150%;
            background-color: red;
        }

        /* Bottom Navigation */
        .btm-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 62px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-top: 1px solid #ddd;
            box-shadow: 0 -1px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.4s ease-in-out;
            transform: translateY(0);
            z-index: 999;
        }

        .btm-nav .nav-item {
            text-align: center;
            color: #666;
            text-decoration: none;
            font-size: 12px;
            flex: 1;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .btm-nav .nav-item i {
            font-size: 20px;
            display: block;
            margin-bottom: 2px;
            transition: color 0.3s ease;
        }

        .btm-nav .nav-item span {
            font-size: 11px;
            display: block;
            margin-top: 3px;
        }

        .btm-nav .nav-item.active {
            color: #ff3131;
            background: #e9e9e9;
        }

        .btm-nav .nav-item.active i {
            color: #ff3131;
        }

        .btm-nav .nav-item.active::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 34px;
            height: 34px;
            background-color: rgba(255, 49, 49, 0.1);
            border-radius: 50%;
            z-index: -1;
        }

        /* Just In Widget */

        .just_in {
            padding: 1px 14px 0;
            height: 287px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #c5c5c5;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .js_t {
            position: relative;
            color: red;
            font-size: 17px;
            line-height: 25px;
            font-weight: 600;
            margin-left: 28px;
            margin-bottom: 12px;
            margin-top: 10px;
        }

        .js_t::before,
        .js_t::after {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            border-radius: 50%;
            left: -29px;
            background-color: #ff1a1a;
            top: 5px;
        }

        .js_t::after {
            width: 16px;
            height: 16px;
            animation: pulse 1s linear infinite;
        }

        @keyframes pulse {
            from {
                transform: scale(0.8);
                opacity: 1;
            }

            to {
                transform: scale(1.8);
                opacity: 0;
            }
        }

        .js_block {
            padding: 0 6px 0 0;
            height: 230px;
            overflow-y: auto;
            list-style: none;
            padding-left: 0;
            margin-left: 0;
        }

        .js_block li {
            padding-left: 6px;
        }

        .js_block::-webkit-scrollbar {
            width: 3px;
        }

        .js_block::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }

        .js_block::-webkit-scrollbar-thumb {
            background: #c0c0c0;
            border-radius: 10px;
        }

        .js_block::-webkit-scrollbar-thumb:hover {
            background: #444444;
        }

        .js_article {
            display: flex;
            gap: 15px;
            padding-left: 10px;
            margin-bottom: 4px;
        }

        .js_right {
            margin-bottom: 4px;
        }

        .js_right p {
            margin-bottom: 3px;
            font-size: 14px;
            color: #5f5f5f;
            font-weight: 600;

        }

        .js_right a {
            font-size: 15px;
            color: #666;
            font-weight: 400;
            line-height: 22px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .js_right a:hover {
            color: red;
        }

        .js_left {
            width: 2px;
            height: 55px;
            background: #d2cdcd;
            position: relative;
            margin-top: 19px;
        }

        .js_left::after {
            position: absolute;
            content: "";
            height: 8px;
            width: 8px;
            border-radius: 50%;
            left: -3px;
            background-color: #ff1a1a;
            top: -14px;
        }

        /* Footer Mobile */
        .footer_main {
            padding: 40px 15px 50px;
            margin-top: 40px;
            background-color: #000000;
        }

        .footer-top {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding-bottom: 24px;
        }

        .footer_left {
            width: 100%;
            padding-bottom: 10px;
            border-bottom: 1px solid #4b4b4b;
        }

        .footer_logo {
            display: inline-block;
        }

        .footer_logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .footer_logo_wrap {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-bottom: 10px;
        }

        .footer_logo_wrap .footer_logo:first-child {
            width: 73px;
        }

        .footer_logo_wrap .footer_logo:last-child {
            width: 150px;
        }

        .footer_left p {
            max-width: 294px;
            color: #efefef;
            font-size: 18px;
            margin-bottom: 0;
        }

        .contact_wrap {
            padding-top: 20px;
        }

        .contact_block {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }

        .contact_block .ct_left {
            color: #848484;
        }

        .contact_block .ct_left i {
            font-size: 20px;
        }

        .contact_block .ct_right {
            display: flex;
            flex-direction: column;
        }

        .contact_block .ct_right small {
            color: #848484;
        }

        .contact_block .ct_right a,
        .contact_block .ct_right p {
            color: #fff;
            font-size: 16px;
            margin: 0;
        }

        .footer_centre {
            width: 100%;
            display: flex;
            padding-left: 0px;
            padding-right: 0px;
            border-bottom: 1px solid #4b4b4b;
            padding-bottom: 10px;
            justify-content: space-around;
        }

        .footer_centre .footer_col {
            display: flex;
            flex-direction: column;
        }

        .footer_col h4 {
            color: white;
            margin-bottom: 15px;
        }

        .footer_menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
            list-style: none;
            padding-left: 0;
        }

        .footer_menu .footer_item {
            margin-bottom: 10px;
        }

        .footer_menu .footer_item a {
            color: white;
            text-decoration: none;
            font-size: 16.5px;
            line-height: 20px;
            transition: color 0.3s ease;
        }

        .footer_menu .footer_item a:hover {
            color: #ff3131;
        }

        .footer_centre .footer_col:nth-child(2) {
            padding-left: 15px;
        }

        .footer_centre .footer_col:nth-child(2) .footer_menu {
            flex-direction: column;
        }

        .footer_right {
            width: 100%;
            display: flex;
            flex-direction: column;
            padding-left: 0px;
        }

        .footer_right h5 {
            color: #fff;
            font-size: 20px;
            max-width: 180px;
            margin-bottom: 17px;
        }

        .app_btn_wrap {
            display: flex;
            flex-direction: row;
            gap: 14px;
        }

        .playstore-button {
            width: 154px;
            display: inline-flex;
            align-items: center;
            border: 2px solid #8f8f8f;
            border-radius: 12px;
            background: rgba(0, 0, 0, 1);
            padding: 9px 15px;
            color: rgba(255, 255, 255, 1);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .playstore-button:hover {
            background: transparent;
        }

        ._icon {
            width: 1.4rem;
        }

        .texts {
            margin-left: 1rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1;
        }

        .text-1 {
            margin-bottom: 0.25rem;
            font-size: 0.65rem;
        }

        .text-2 {
            font-weight: 600;
            font-size: 14px;
        }

        .footer-site-info {
            color: #848484;
        }

        .poweredby span {
            color: #848484;
        }

        .poweredby {
            display: flex;
            flex-direction: column;
            align-items: anchor-center;
            gap: 14px;
        }
    </style>

    <!-- AMP Boilerplate -->
    <style amp-boilerplate>
        body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both
        }

        @-webkit-keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }

        @-moz-keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }

        @-ms-keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }

        @-o-keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }

        @keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }
    </style>
    <noscript>
        <style amp-boilerplate>
            body {
                -webkit-animation: none;
                -moz-animation: none;
                -ms-animation: none;
                animation: none
            }
        </style>
    </noscript>


  <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": {!! json_encode(Str::limit($data['blog']->name, 110)) !!},
        "description": {!! json_encode(Str::limit(strip_tags($data['blog']->sort_description ?? $data['blog']->description), 200)) !!},
        "image": [
            @if (!empty($imageToUse))
                "{{ $imageToUse }}"
            @else
                "{{ $customImageUrl }}"
            @endif
        ],
        "author": {
            "@type": "Person",
            "name": {!! json_encode($data['author']->name ?? 'NMF News') !!}
        },
        "publisher": {
            "@type": "Organization",
            "name": "NMF News",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('/frontend/images/logo.png') }}"
            }
        },
        "datePublished": "{{ $data['blog']->created_at->toIso8601String() }}",
        "dateModified": "{{ $data['blog']->updated_at->toIso8601String() }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
        }
    }
    </script>

    {{-- We use the logic from your controller to check for a YouTube ID or an .mp4 file --}}
    @if ($data['youtubeVideoId'] || str_contains($data['blog']->link ?? '', '.mp4'))
        <script type="application/ld+json">
        {!! json_encode([
            "@context" => "https://schema.org",
            "@type" => "VideoObject",
            "name" => $data['blog']->name,
            "description" => Str::limit(strip_tags($data['blog']->sort_description ?? $data['blog']->description), 200),
            "thumbnailUrl" => asset(config('global.blog_images_everywhere')($data['blog'])),
            "uploadDate" => \Carbon\Carbon::parse($data['blog']->created_at)->toIso8601String(),
            "contentUrl" => $data['blog']->link,
            "embedUrl" => $data['youtubeVideoId'] 
                            ? "https://www.youtube.com/embed/" . $data['youtubeVideoId'] 
                            : (str_contains($data['blog']->link, '.mp4') ? $data['blog']->link : null),
            "publisher" => [
                "@type" => "Organization",
                "name" => "NMF News",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => "https://newsnmf.com/frontend/images/logo.png",
                    "width" => 300,
                    "height" => 60,
                ]
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ url('/') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "राज्य",
                "item": "{{ url('/states') }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": {!! json_encode($data['blog']->name) !!},
                "item": "{{ url()->current() }}"
            }
        ]
    }
    </script>
</head>

<body>
    <amp-state id="ui">
        <script type="application/json">
        {
            "readMore": false
        }
        </script>
    </amp-state>
    <amp-analytics type="gtag" data-credentials="include">
        <script type="application/json">
  {
    "vars": {
      "gtag_id": "G-9D3VCPPRWL", 
      "config": {
        "G-9D3VCPPRWL": { "groups": "default" }
      }
    },
    "triggers": {
      "scrollAfter10s": {
        "on": "visible",
        "request": "hitCount",
        "visibilitySpec": {
          "selector": "body",
          "visiblePercentageMin": 10,
          "totalTimeMin": 10000
        }
      }
    },
    "requests": {
      "hitCount": "/increase-webhitcount?blog_id={{ $data['blog']->id }}"
    }
  }
  </script>
    </amp-analytics>
    <amp-font layout="nodisplay" font-family="Noto Sans Devanagari" timeout="3000"
        on-error-remove-class="noto-sans-loading" on-load-add-class="noto-sans-loaded">
    </amp-font>
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
@include('layouts.header-amp')
    <div class="article--container">
        <!-- Breadcrumb -->
        <nav class="article-breadcrumb" aria-label="Breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item">
                    <a href="/" rel="home">Home</a>
                </li> <svg fill="#666" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15"
                    height="15"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M439.1 297.4C451.6 309.9 451.6 330.2 439.1 342.7L279.1 502.7C266.6 515.2 246.3 515.2 233.8 502.7C221.3 490.2 221.3 469.9 233.8 457.4L371.2 320L233.9 182.6C221.4 170.1 221.4 149.8 233.9 137.3C246.4 124.8 266.7 124.8 279.2 137.3L439.2 297.3z" />
                </svg>
                @if (!empty($data['category']))
                    <li class="breadcrumb-item">
                        <a href="{{ url($data['category']->site_url) }}">
                            {{ $data['category']->name }}
                        </a>
                    </li>
                @endif
            </ol>
        </nav>

        <!-- Header Ad -->
        <div class="ad-container">
            <p class="ad-title">Advertisement</p>
            <amp-ad layout="responsive" width="300" height="300" type="adsense"
                data-ad-client="ca-pub-3986924419662120" data-ad-slot="3774348576">
            </amp-ad>
        </div>
        <div class="article--main-grid">
            <div class="article--main-content">
                <!-- Article Header -->
                <header class="article--header">
                    <h1 class="article--title">{{ $data['blog']->name }}</h1>
                    <h2 class="article--subtitle">{{ $data['blog']->sort_description }}</h2>
                </header>

                <!-- Article Meta -->
                <div class="article--meta">
                    <div class="article--meta-item">
                        <span>Created By:</span>
                        <a class="l1"
                            href="{{ asset('/author') }}/{{ str_replace(' ', '_', $data['author']->url_name ?? '-') }}">
                            {{ $data['author']->name ?? 'NMF News' }}
                        </a>
                    </div>

                    <div class="article--meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                            viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path
                                d="M96.5 160L96.5 309.5C96.5 326.5 103.2 342.8 115.2 354.8L307.2 546.8C332.2 571.8 372.7 571.8 397.7 546.8L547.2 397.3C572.2 372.3 572.2 331.8 547.2 306.8L355.2 114.8C343.2 102.7 327 96 310 96L160.5 96C125.2 96 96.5 124.7 96.5 160zM208.5 176C226.2 176 240.5 190.3 240.5 208C240.5 225.7 226.2 240 208.5 240C190.8 240 176.5 225.7 176.5 208C176.5 190.3 190.8 176 208.5 176z" />
                        </svg>
                        <a class="l2" href="/{{ $data['category']->site_url ?? '' }}">
                            {{ $data['category']->name }}
                        </a>
                    </div>

                    <div class="article--meta-item">
                        <span>{{ $data['blog']->created_at->format('d M, Y') }}</span>
                        @if ($data['blog']->updated_at)
                            <span>(Updated: {{ $data['blog']->updated_at->format('d M, Y h:i A') }})</span>
                        @endif
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="article--actions">
                    <div class="article--share-container">
                        <button class="article--share-button" on="tap:article-share-dropdown.toggleVisibility">
                            <svg viewBox="0 0 512 512" width="16" height="16">
                                <path fill="currentColor"
                                    d="M307 34.8c-11.5 5.1-19 16.6-19 29.2v64H176C78.8 128 0 206.8 0 304C0 417.3 81.5 467.9 100.2 478.1c2.5 1.4 5.3 1.9 8.1 1.9c10.9 0 19.7-8.9 19.7-19.7c0-7.5-4.3-14.4-9.8-19.5C108.8 431.9 96 414.4 96 384c0-53 43-96 96-96h96v64c0 12.6 7.4 24.1 19 29.2s25 3 34.4-5.4l160-144c6.7-6.1 10.6-14.7 10.6-23.8s-3.8-17.7-10.6-23.8l-160-144c-9.4-8.5-22.9-10.6-34.4-5.4z" />
                            </svg>
                            <span>Share</span>
                        </button>
                        <div id="article-share-dropdown" class="article--share-dropdown" hidden>
                            <ul class="article--social-links">
                                @php
                                    $catname = $data['category']->site_url ?? '';
                                    $slug = $data['blog']->site_url;
                                    $shareUrl = url("$catname/$slug");
                                @endphp
                                <li class="article--social-link">
                                    <a href="http://www.facebook.com/sharer.php?u={{ $shareUrl }}"
                                        target="_blank">
                                        <svg fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                                            width="20" height="20">
                                            <path fill="currentColor"
                                                d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" />
                                        </svg>
                                    </a>
                                </li>
                                <li class="article--social-link">
                                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode($data['blog']->name) }}"
                                        target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"
                                            height="20">
                                            <path fill="currentColor"
                                                d="M389.2 48H470L305.1 232.3 500 464H345.5L233.7 325.3 106.8 464H26.1L200.6 268.7 10 48H169.7L269.4 175.4 389.2 48ZM362.8 424H403.1L153.9 84H111.1L362.8 424Z" />
                                        </svg>

                                    </a>
                                </li>
                                <li class="article--social-link">
                                    <a href="https://api.whatsapp.com/send?text={{ $shareUrl }}" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"
                                            height="20">
                                            <path fill="currentColor"
                                                d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- <a href="#article-comments-section" class="cmmt-button">
                        <svg stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="20" height="20"
                            fill="none">
                            <path
                                d="M8 9h8M8 13h6M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
                        </svg>
                        Comment
                    </a> --}}
                </div>

                <!-- Media Content -->
                <div class="article--media">
                    <div class="article--image-wrapper">

                        @if ($data['youtubeVideoId'])
                            {{-- This is the correct way --}}
                            <amp-youtube data-videoid="{{ $data['youtubeVideoId'] }}" layout="responsive"
                                width="480" height="270">
                            </amp-youtube>
                        @elseif (!empty($data['blog']->link))
                            {{-- This handles non-youtube videos, like .mp4 files --}}
                            <amp-video width="480" height="270" layout="responsive" controls>
                                <source src="{{ $data['blog']->link }}" type="video/mp4">
                            </amp-video>
                        @else
                            {{-- This is the fallback image if there is no video --}}
                            @php
                                $ff = config('global.blog_images_everywhere')($data['blog']);
                            @endphp
                            <amp-img src="{{ !empty($ff) ? asset($ff) : asset('default.jpg') }}"
                                alt="{{ $data['blog']->name }}" layout="responsive" width="800" height="450">
                            </amp-img>
                        @endif

                        @if (!empty($data['blog']->credits))
                            <div class="article--image-credit">
                                <span>{{ $data['blog']->credits }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Follow Section -->

                <div class="follow_us">
                    <div class="follow_us_bar"></div>
                    <div class="flw_wrap">
                        <h6>Follow Us: </h6>
                        <div class="follow_us_socials">
                            <a href="https://www.facebook.com/officialnmfnews" target="_blank" rel="noopener"
                                title="Facebook" class="socials-item">
                                <!-- Facebook SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 320 512" class="facebook">
                                    <path fill="currentColor"
                                        d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" />
                                </svg>
                            </a>

                            <a href="https://x.com/NMFNewsOfficial" target="_blank" rel="noopener" title="Twitter"
                                class="socials-item">
                                <!-- Twitter/X SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 512 512" class="fa-x-twitter">
                                    <path fill="black"
                                        d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                                </svg>
                            </a>

                            <a href="https://instagram.com/nmfnewsofficial" target="_blank" rel="noopener"
                                title="Instagram" class="socials-item">
                                <!-- Instagram SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 448 512" class="instagram">
                                    <path fill="currentColor"
                                        d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                                </svg>
                            </a>

                            <a href="https://www.youtube.com/c/NMFNews/featured" target="_blank" rel="noopener"
                                title="YouTube" class="socials-item">
                                <!-- YouTube SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 576 512" class="youtube">
                                    <path fill="currentColor"
                                        d="M549.7 124.1c-6.3-23.7-24.8-42.3-48.3-48.6C458.8 64 288 64 288 64S117.2 64 74.6 75.5c-23.5 6.3-42 24.9-48.3 48.6-11.4 42.9-11.4 132.3-11.4 132.3s0 89.4 11.4 132.3c6.3 23.7 24.8 41.5 48.3 47.8C117.2 448 288 448 288 448s170.8 0 213.4-11.5c23.5-6.3 42-24.2 48.3-47.8 11.4-42.9 11.4-132.3 11.4-132.3s0-89.4-11.4-132.3zm-317.5 213.5V175.2l142.7 81.2-142.7 81.2z" />
                                </svg>
                            </a>

                            <a href="https://whatsapp.com/channel/0029VajdZqv9xVJbRYtSFM3C" target="_blank"
                                rel="noopener" title="WhatsApp" class="socials-item">
                                <!-- WhatsApp SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 448 512" class="whatsapp">
                                    <path fill="currentColor"
                                        d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="follow_us_bar"></div>
                </div>

                <!-- Article Content -->
                <!-- Article Content -->
                {{-- <div class="readmore" [class]="ui.readMore ? 'readmore expanded' : 'readmore'"> --}}
                <div class="article--content">
                    <div class="amp-content">
                        {!! config('global.sanitize_amp_content')($data['blog']->description ?? '') !!}
                    </div>

                </div>
                @php
                    // Get full content
                    $description = $data['blog']->description ?? '';
                    // ------------------
                    $paragraphs = preg_split(
                        '/(<\/p>)/i',
                        $description,
                        -1,
                        PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY,
                    );
                    $pCount = preg_match_all('/<p[^>]*>.*?<\/p>/is', $description);
                    $divCount = preg_match_all('/<div[^>]*>.*?<\/div>/is', $description);

                    $blockCount = $pCount + $divCount;
                    $injectionIndex = $blockCount > 1 ? $blockCount - 1 : 0;
                    $injected = false;
                    $count = 0;

                    // Helper: detect if block is pseudo-heading (<p><strong>...</strong></p> only)
                    function isPseudoHeading($html)
                    {
                        return preg_match('/^<p[^>]*>\s*<strong>.*<\/strong>\s*<\/p>$/is', trim($html));
                    }
                @endphp

                @if ($injectionIndex === 0)
                    @if (!empty($data['latests']) && $data['latests']->isNotEmpty())
                        @include('components.related-articles', [
                            'articles' => $data['latests'],
                        ])
                    @endif
                    @php $injected = true; @endphp
                @endif

                <div class="ad-container">
                    <p class="ad-title">Advertisement</p>
                    <amp-ad layout="responsive" width="300" height="300" type="adsense"
                        data-ad-client="ca-pub-3986924419662120" data-ad-slot="3774348576">
                    </amp-ad>
                </div>
                @foreach ($paragraphs as $block)

                    @if (preg_match('/<\/p>/i', $block))
                        @php $count++; @endphp

                        @if (!$injected && $count === $injectionIndex)
                            {{-- If this block is a pseudo-heading, inject BEFORE it --}}
                            @if (isPseudoHeading($block))
                                @if (!empty($data['latests']) && $data['latests']->isNotEmpty())
                                    @include('components.related-articles', [
                                        'articles' => $data['latests'],
                                    ])
                                @endif
                                @php $injected = true; @endphp
                            @else
                                @if (!empty($data['latests']) && $data['latests']->isNotEmpty())
                                    @include('components.related-articles', [
                                        'articles' => $data['latests'],
                                    ])
                                @endif
                                @php $injected = true; @endphp
                            @endif
                        @endif
                    @endif
                @endforeach
                @if (!$injected)
                    @if (!empty($data['latests']) && $data['latests']->isNotEmpty())
                        @include('components.related-articles', [
                            'articles' => $data['latests'],
                        ])
                    @endif
                @endif


                <!-- WhatsApp Button -->
                <div class="wp-btn">
                    <a href="https://whatsapp.com/channel/0029VajdZqv9xVJbRYtSFM3C" class="article--whatsapp-button"
                        target="_blank">
                        व्हॉट्सऐप चैनल से जुड़ें
                        <svg viewBox="0 0 48 48" y="0px" x="0px" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z"
                                fill="#fff"></path>
                            <path
                                d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z"
                                fill="#fff"></path>
                            <path
                                d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z"
                                fill="#cfd8dc"></path>
                            <path
                                d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z"
                                fill="#40c351"></path>
                            <path clip-rule="evenodd"
                                d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z"
                                fill-rule="evenodd" fill="#fff"></path>
                        </svg>
                    </a>
                </div>
                <div class="ad-container">
                    <p class="ad-title">Advertisement</p>
                    <amp-ad layout="responsive" width="300" height="300" type="adsense"
                        data-ad-client="ca-pub-3986924419662120" data-ad-slot="3774348576">
                    </amp-ad>
                </div>
                <!-- Tags Section -->
                @if (!empty($data['blog']->tags))
                    <section class="article--tags-section">
                        <h3 class="article--tags-title">Tags</h3>
                        <div class="article--tags-container">
                            @foreach (explode(',', $data['blog']->tags) as $tag)
                                @if (trim($tag) !== '')
                                    <a href="{{ url('/search?search=' . trim($tag)) }}" class="article--tag">
                                        {{ trim($tag) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <!-- Middle Horizontal Ad -->
                        <div class="ad-container">
                            <p class="ad-title">Advertisement</p>
                            <amp-ad layout="responsive" width="300" height="300" type="adsense"
                                data-ad-client="ca-pub-3986924419662120" data-ad-slot="3774348576">
                            </amp-ad>
                        </div>
                    </section>
                @endif

                <!-- Fade effect - moved outside article--content but inside readmore -->
                {{-- <div class="readmore__fade" [hidden]="ui.readMore"></div> --}}
                {{--
                </div> --}}

                <!-- Read More Actions -->
                {{-- <div class="readmore__actions">
                    <button class="readmore__btn" on="tap:AMP.setState({ui: {readMore: true}})" [hidden]="ui.readMore">
                        Read more
                    </button>

                </div> --}}
                <div class="col_right">
                    {{-- - 10 latest articles displayed - --}}
                    @include('components.latestStories-amp')


                    {{-- Vertical-Small-1 Advertise --}}
                    {{-- <x-vertical-sm-ad :ad="$data['detailsAds']['detail_sidebar_vertical_ad1'] ?? null" /> --}}

                    @php
                        $categories = [
                            ['name' => 'ट्रेंडिंग न्यूज़', 'limit' => 5],
                            ['name' => 'पॉडकास्ट', 'limit' => 1],
                            ['name' => 'टेक्नोलॉजी', 'limit' => 5],
                            ['name' => 'स्पेशल्स', 'limit' => 5],
                        ];
                    @endphp

                    @foreach ($categories as $cat)
                        @php
                            $category = App\Models\Category::where('name', $cat['name'])->first();
                            $blogs = App\Models\Blog::where('status', 1)
                                ->where('categories_ids', $category->id ?? 0)
                                ->where('id', '!=', $data['blog']->id) //  EXCLUDE CURRENT BLOG
                                ->orderBy('updated_at', 'desc')
                                ->limit($cat['limit'])
                                ->get();
                        @endphp

                        @if ($blogs->isNotEmpty())
                            @include('components.side-widgets-amp', [
                                'categoryName' => $cat['name'],
                                'category' => $category,
                                'blogs' => $blogs,
                            ])
                        @endif
                    @endforeach

                    {{-- Vertical-Small-2 Advertise --}}
                    {{-- <x-vertical-sm-ad :ad="$data['detailsAds']['detail_sidebar_vertical_ad2'] ?? null" /> --}}

                </div>
            </div>
@include('layouts.footer-amp')
            <amp-analytics id="scroll-hitcount" type="gtag" data-credentials="include">
</body>

</html>
