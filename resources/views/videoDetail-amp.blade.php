<!doctype html>
<html amp lang="en">

<head>
    @php
        $canonicalUrl = str_replace('/amp', '/', url()->current());
    @endphp
    <meta charset="utf-8">
    <title>{{ $video->title ?? 'Video Detail' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="canonical" href="{{ $canonicalUrl }}" />
    <script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-font" src="https://cdn.ampproject.org/v0/amp-font-0.1.js"></script>
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
       video-amp css
       --------------------------- */
        .at_img {
            margin-top: 15px;
            margin-bottom: 12px;
        }

        .amp_video {
            border-radius: 8px;
        }

        .rt_sub2 {
            margin-bottom: 15px;
        }

        .rel_content {
            list-style: none;
        }

        .col_right {
            padding-left: 15px;
            padding-right: 15px;
        }

        .rel_artcle_wrap {
            margin-bottom: 10px
        }

        .playBtn-wrap2 {
            left: -1px;
            max-width: 74px;
            position: absolute;
            bottom: 0px;
            padding: 1px 7px 1px 7px;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #0e0e0e52;
            gap: 3px;
            z-index: 2;
            backdrop-filter: blur(1px);
        }

        .video-items-right .custom-tab-card .playBtn-wrap2 .play-btn {
            color: #fffff;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 13px;
        }

        .play-btn i {
            color: #ffff;
        }

        .video-items-right .custom-tab-card .playBtn-wrap2 .play-btn i {
            color: #fff;
            transition: all 0.3s ease-in-out 0s;
            -webkit-transition: all 0.3s ease-in-out 0s;
            -moz-transition: all 0.3s ease-in-out 0s;
            -ms-transition: all 0.3s ease-in-out 0s;
            -o-transition: all 0.3s ease-in-out 0s;
        }

        .v-duration2 {
            margin: 0;
            color: #fff;
            font-size: 12px;
            margin-left: 6px;
            padding-left: 6px;
            border-left: 1px solid #ffffffe1;
            padding-top: 1px;
            margin-top: -1px;
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
            position: relative;
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


    {{-- Required script for amp-video --}}

    {{-- Required script for amp-ad --}}
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>

</head>

<body>
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
    <header class="--header-amp">
        <div class="cm-container">
            <div class="--header-container">
                <div class="--header-left">
                    <a href="/" class="--nmf-logo-amp">
                        {{-- <img src="{{ asset('/frontend/images/logo.png') }}" alt="Logo" class="--logo" /> --}}
                        <img src="https://www.newsnmf.com/frontend/images/logo.png" alt="Logo" class="--logo" />
                    </a>
                </div>
                <div class="--header-right">
                    <div class="--header-right-top">
                        <div class="--hdr-top">
                            <div class="--hdr-t-l">

                                <button class="--toggle-box" on="tap:ampModalMenu.open" id="toggle-btn">
                                    <label class="burger" for="burger">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </label>
                                </button>
                                <div>

                                </div>
                            </div>


                            <small class="Headertag m-0 htag" style="margin-left: 0px;white-space: nowrap;"> <span
                                    class="" style="color: #fff;">जिस पर देश</span><span
                                    class="HeadertagHalf">करता है
                                    भरोसा</span> </small>
                        </div>

                        <amp-lightbox id="ampModalMenu" layout="nodisplay">
                            <div class="modal-content">
                                <div class="modal_top">
                                    <button class="close_btn" on="tap:ampModalMenu.close" tabindex="0" role="button"
                                        aria-label="Close menu">
                                        ✕
                                    </button>
                                    <a href="/" class="modal_logo">
                                        <!-- CONVERSION TO AMP: Img tag replaced with amp-img, width and height are mandatory -->
                                        <amp-img src="https://www.newsnmf.com/frontend/images/logo.png"
                                            alt="NMF News Logo" width="50" height="48" layout="fixed"></amp-img>
                                    </a>
                                    <span class="Headertag" style="margin-left: 0px">
                                        <span style="color: #333;">जिस पर देश</span>
                                        <span class="HeadertagHalf">करता है भरोसा</span>
                                    </span>
                                </div>

                                <?php
                                // Define category-to-icon mapping
                                $categoryIcons = [
                                    'होम' => 'fa-solid fa-house',
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
                                    'विधान सभा चुनाव' => 'fa-solid fa-person-booth',
                                    'क्राइम' => 'fa-solid fa-user-secret',
                                    'वेब स्टोरी' => 'fa-solid fa-photo-film',
                                    'यूटीलिटी' => 'fa-solid fa-wrench',
                                    'करियर' => 'fa-solid fa-briefcase',
                                    'ट्रेंडिंग न्यूज़' => 'fa-solid fa-bolt',
                                    'ब्लॉग' => 'fa-solid fa-pen-nib',
                                ];
                                
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
                                        // Submenu logic remains untouched, only used to determine structure
                                        $subMenus = App\Models\Menu::where('menu_id', $menu->id)->where('status', 1)->where('type_id', 1)->where('category_id', 2)->orderBy('sequence_id', 'asc')->get();
                                        $hasSubMenus = count($subMenus) > 0;
                                        ?>

                                        <li class="modal_item">
                                            @if ($hasSubMenus)
                                                <!-- AMP Accordion for the collapsible section -->
                                                <amp-accordion class="amp-menu-toggle" animate>
                                                    <!-- The <section> defines one accordion item -->
                                                    <section>
                                                        <!-- H4 is the required header element and acts as the toggle button for the content below -->
                                                        <h4 class="amp-menu-header">
                                                            <!-- The icon and menu name remain, but the H4 is NOT a link to prevent conflict with accordion toggle -->
                                                            <i
                                                                class="{{ $categoryIcons[$menu->menu_name] ?? 'fa-solid fa-link' }}"></i>
                                                            {{ $menu->menu_name }}
                                                        </h4>

                                                        <!-- The div is the collapsible content -->
                                                        <div class="amp-menu-submenu-content">
                                                            <!-- Since the H4 is the toggle, we add the main category link inside the dropdown content -->
                                                            <a href="{{ asset($menu->menu_link) }}"
                                                                class="amp-menu-parent-link-in-dropdown">
                                                                {{ $menu->menu_name }}
                                                            </a>

                                                            <ul class="modal_submenu">
                                                                @foreach ($subMenus as $subMenu)
                                                                    <li>
                                                                        <a href="{{ asset($subMenu->menu_link) }}">
                                                                            <!-- Simple unicode circle for maximum AMP compliance and icon consistency -->
                                                                            <span
                                                                                style="font-size: 0.5em; vertical-align: middle; margin-right: 8px;">&#9679;</span>
                                                                            {{ $subMenu->menu_name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </section>
                                                </amp-accordion>
                                            @else
                                                <!-- Simple link if no submenus -->
                                                <a href="{{ asset($menu->menu_link) }}">
                                                    <i
                                                        class=" {{ $categoryIcons[$menu->menu_name] ?? 'fa-solid fa-link' }}"></i>
                                                    {{ $menu->menu_name }}
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </amp-lightbox>
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

                @foreach ($menus as $item)
                    @php
                        if (substr($item['menu_link'], 0, 1) !== '/') {
                            $item['menu_link'] = '/' . $item['menu_link'];
                        }
                        $fullUrl =
                            (substr($baseUrl, -1) === '/' ? substr($baseUrl, 0, -1) : $baseUrl) . $item['menu_link'];
                    @endphp
                    <li
                        class="menu-item {{ request()->is('/') && $item['menu_link'] === '/' ? 'active' : '' }}{{ request()->is(ltrim($item['menu_link'], '/')) && $item['menu_link'] !== '/' ? 'active' : '' }}">
                        <a href="{{ $fullUrl }}"
                            class="menu-link {{ str_contains($item['menu_link'], 'state-legislative-assembly-election') ? 'mobile-new' : '' }}">{{ $item['menu_name'] }}
                        </a>
                    </li>
                @endforeach


            </ul>
        </div>
    </nav>
    <div class="article--container">
        <!-- Breadcrumb -->
        <nav class="article-breadcrumb" aria-label="Breadcrumb">
            <ol class="breadcrumb-list">
                {{-- 1. Home Link --}}
                <li class="breadcrumb-item">
                    <a href="/" rel="home">Home</a>
                    <meta itemprop="position" content="1">
                </li>

                {{-- Separator SVG --}}
                <svg fill="#666" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15"
                    height="15">
                    <path
                        d="M439.1 297.4C451.6 309.9 451.6 330.2 439.1 342.7L279.1 502.7C266.6 515.2 246.3 515.2 233.8 502.7C221.3 490.2 221.3 469.9 233.8 457.4L371.2 320L233.9 182.6C221.4 170.1 221.4 149.8 233.9 137.3C246.4 124.8 266.7 124.8 279.2 137.3L439.2 297.3z" />
                </svg>

                {{-- 2. Video Archive Link (New/Inserted) --}}
                <li class="breadcrumb-item">
                    <a href="{{ url('/videos') }}">
                        Video
                    </a>
                    <meta itemprop="position" content="2">
                </li>

                {{-- Separator SVG --}}
                <svg fill="#666" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15"
                    height="15">
                    <path
                        d="M439.1 297.4C451.6 309.9 451.6 330.2 439.1 342.7L279.1 502.7C266.6 515.2 246.3 515.2 233.8 502.7C221.3 490.2 221.3 469.9 233.8 457.4L371.2 320L233.9 182.6C221.4 170.1 221.4 149.8 233.9 137.3C246.4 124.8 266.7 124.8 279.2 137.3L439.2 297.3z" />
                </svg>

                {{-- 3. Video Category Link --}}
                <li class="breadcrumb-item">
                    {{-- NOTE: Assuming $data['category'] contains the current category object --}}
                    <a href="{{ url('/videos/' . ($video->category->site_url ?? '')) }}">
                        {{ $video->category->name }}
                    </a>
                    <meta itemprop="position" content="3">
                </li>

            </ol>
        </nav>


        {{-- Horizontal-1 Ad: Replaced custom component with amp-ad --}}

        {{-- Left Column --}}
        <div class="article--main-grid">
            <div class="article--main-content">
                <!-- Article Header -->
                <header class="article--header">
                    <h1 class="article--title">{{ $video->title }}</h1>
                </header>
                {{-- Metadata --}}

                <!-- Video Meta -->
                <div class="article--meta">
                    <div class="article--meta-item">
                        <span>Created By:</span>
                        <a class="l1"
                            href="{{ url('/author/' . str_replace(' ', '_', $video->author->url_name ?? '-')) }}">
                            {{ $video->author->name ?? 'NMF News' }}
                        </a>
                    </div>

                    <div class="article--meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                            viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path
                                d="M96.5 160L96.5 309.5C96.5 326.5 103.2 342.8 115.2 354.8L307.2 546.8C332.2 571.8 372.7 571.8 397.7 546.8L547.2 397.3C572.2 372.3 572.2 331.8 547.2 306.8L355.2 114.8C343.2 102.7 327 96 310 96L160.5 96C125.2 96 96.5 124.7 96.5 160zM208.5 176C226.2 176 240.5 190.3 240.5 208C240.5 225.7 226.2 240 208.5 240C190.8 240 176.5 225.7 176.5 208C176.5 190.3 190.8 176 208.5 176z" />
                        </svg>
                        <a class="l2" href="/{{ $video->category->site_url ?? '' }}">
                            {{ $video->category->name }}
                        </a>
                    </div>

                    <div class="article--meta-item">
                        <span>{{ $video->created_at->format('d M, Y') }}</span>
                        @if ($video->updated_at != $video->created_at)
                            <span>(Updated: {{ $video->updated_at->format('d M, Y h:i A') }})</span>
                        @endif
                    </div>
                </div>

                {{-- Share Buttons (Simplified - assuming share button JS is removed) --}}
                <div class="at_right">
                    @php
                        $videoUrl =
                            'https://www.newsnmf.com/video/' .
                            ($video->category->site_url ?? '-') .
                            '/' .
                            $video->site_url;
                        $videoTitle = $video->title ?? 'Check out this video!'; // Assuming $video->title exists for better shares
                    @endphp

                    <div class="c-row ">
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

                                    <li class="article--social-link">
                                        <a href="http://www.facebook.com/sharer.php?u={{ $videoUrl }}"
                                            target="_blank">
                                            <svg fill="black" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 320 512" width="20" height="20">
                                                <path fill="currentColor"
                                                    d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="article--social-link">
                                        <a href="https://twitter.com/intent/tweet?url={{ $videoUrl }}"
                                            target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                                width="20" height="20">
                                                <path fill="currentColor"
                                                    d="M389.2 48H470L305.1 232.3 500 464H345.5L233.7 325.3 106.8 464H26.1L200.6 268.7 10 48H169.7L269.4 175.4 389.2 48ZM362.8 424H403.1L153.9 84H111.1L362.8 424Z" />
                                            </svg>

                                        </a>
                                    </li>
                                    <li class="article--social-link">
                                        <a href="https://api.whatsapp.com/send?text={{ $videoUrl }}"
                                            target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                width="20" height="20">
                                                <path fill="currentColor"
                                                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="at_img">
                            <figure>
                                @if ($video->video_path)
                                    {{-- The <amp-video> component MUST have explicit width and height. --}}
                                    <amp-video class="amp_video" id="custom_video" width="16" height="9"
                                        layout="responsive" poster="{{ asset($video->thumbnail_path) }}" autoplay
                                        muted controls>
                                        <source src="{{ config('global.base_url_videos') . $video->video_path }}"
                                            type="video/mp4">
                                        <div fallback>Your browser does not support
                                            the video
                                            tag.</div>
                                    </amp-video>
                                @elseif ($video->thumbnail_path)
                                    {{-- Replaced <img> with <amp-img> --}}
                                    <amp-img src="{{ asset($video->thumbnail_path) }}" alt="{{ $video->title }}"
                                        width="16" height="9" layout="responsive">
                                    </amp-img>
                                @endif
                            </figure>
                        </div>

                        {{-- Description --}}
                        <div class="at_content">
                            <p class="rt_sub2 mt-1">
                                {!! $video->description !!}
                            </p>

                            {{-- Google Ad --}}
                            <section class="cm_related_post_container">
                                <div class="section_inner">

                                    {{-- Horizontal-Small-1 Advertise: Replaced custom component with amp-ad --}}
                                    {{-- <div class="amp-ad-container">
                                        
                                        <amp-ad width="320" height="50" type="adsense"
                                            data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="XXXXXXXXXX"
                                            layout="responsive">
                                        </amp-ad>
                                    </div> --}}

                                    {{-- Related Videos (content kept, assuming blade loop is safe) --}}
                                    <div class="rel_artcle_wrap">
                                        <h3 class="rel_heading">यह भी पढ़ें</h3>
                                        <ul class="rel_content">
                                            @foreach ($latests as $latest)
                                                <li>
                                                    <article class="rel_article">
                                                        <div class="rel_top">
                                                            <div class="playBtn-wrap2">
                                                                <div class="play-btn"><i class="fa-solid fa-play"></i>
                                                                </div>
                                                                <p class="v-duration2">
                                                                    10:39
                                                                </p>
                                                            </div>
                                                            <a
                                                                href="{{ url('/video/' . ($latest->category->site_url ?? '-') . '/' . $latest->site_url) }}">
                                                                {{-- Replaced <img> with <amp-img> --}}
                                                                <amp-img
                                                                    src="{{ config('global.base_url_videos') . $latest->thumbnail_path }}"
                                                                    alt="{{ $latest->title }}" width="300"
                                                                    height="168" layout="responsive">
                                                                </amp-img>
                                                            </a>
                                                            <a href="{{ url('/videos/' . ($latest->category->site_url ?? '')) }}"
                                                                class="nws_article_strip">
                                                                {{ $latest->category->name }}
                                                            </a>
                                                        </div>
                                                        <div class="rel_bottom">
                                                            <a href="{{ url('/video/' . ($latest->category->site_url ?? '-') . '/' . $latest->site_url) }}"
                                                                class="rel_link">
                                                                {{ $latest->title }}
                                                            </a>
                                                        </div>
                                                    </article>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col_right">
                @include('components.latestStories-amp')

                {{-- Vertical-Small-1 Ad: Replaced custom component with amp-ad --}}
                {{-- <div class="amp-ad-container">
                    <amp-ad width="300" height="600" type="adsense" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                        data-ad-slot="XXXXXXXXXX" layout="responsive">
                    </amp-ad>
                </div> --}}

                {{-- Optimized Side Widgets (Content of include must be AMP valid) --}}
                @foreach ($sideWidgets as $widget)
                    @include('components.side-widgets-amp', [
                        'categoryName' => $widget['categoryName'],
                        'category' => $widget['category'],
                        'blogs' => $widget['blogs'],
                    ])
                @endforeach

                {{-- Vertical-Small-2 Ad: Replaced custom component with amp-ad --}}
                {{-- <div class="amp-ad-container">
                    <amp-ad width="300" height="250" type="adsense" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                        data-ad-slot="XXXXXXXXXX" layout="responsive">
                    </amp-ad>
                </div> --}}
            </div>
        </div>

        {{-- Bottom Ad --}}
        {{-- <div class="amp-ad-container">
            <amp-ad width="728" height="90" type="adsense" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                data-ad-slot="XXXXXXXXXX" layout="responsive">
            </amp-ad>
        </div> --}}
    </div>
    </section>

    </div>
    </main>
    </div>
    </div>
    </div>
    <footer class="footer_main">
        <div class="cm-container">
            <div class="footer-top">
                <div class="footer_left">
                    <div class="footer_logo_wrap">
                        <a href="{{ asset('/') }}" class="footer_logo">
                            <!-- NL1025:20Sept:2025:Added config path -->

                            <img src="{{ config('global.base_url_frontend') }}frontend/images/logo.png"
                                alt="" />
                        </a>
                        <div class="footer_logo">
                            <img src="{{ config('global.base_url_asset') }}asset/images/kmc_logo.png" alt="">
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
                                            <a href="{{ $footer_menu->menu_link }}">{{ $footer_menu->menu_name }}</a>
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

                    @if (session('subscribemessage'))
                        <div class="alert alert-success mt-1">
                            {{ session('subscribemessage') }}
                        </div>
                    @endif



                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="f-row">
                <div class="col-md-6 ps-0">
                    <div class="footer-site-info">Copyright © 2025 KMC PVT. LTD. All Rights Reserved.</div>
                </div>
                <div class="ftcol">
                    <div class="poweredby">
                        <span>Designed & Developed by</span>
                        <!-- NL1025:20Sept:2025:Added config path -->

                        <a href="https://www.abrosys.com/"> <img width="102" height="19"
                                src="{{ config('global.base_url_asset') }}asset/images/abrosys.png"
                                alt="Abrosys Technologies Private Limited"></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <amp-analytics id="scroll-hitcount" type="gtag" data-credentials="include">
</body>

</html>
