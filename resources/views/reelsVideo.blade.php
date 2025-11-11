<!DOCTYPE html>
<html lang="en">
@php
    $video = $videos[$currentIndex];
    $videoUrl = '';
    $videothumb = '';

    if ($video->video_path && $video->clip_file_name && strpos($video->video_path, 'file') !== false) {
        // Video full URL
        $videoPath = str_replace('\\', '/', $video->video_path);
        $folderPath = substr($videoPath, strpos($videoPath, 'file'));
        $videoUrl = config('global.base_url_short_videos') . $folderPath . '/' . $video->clip_file_name;

        // Thumbnail full URL
        $imagePath = str_replace('\\', '/', $video->image_path);
        $thumbFolder = substr($imagePath, strpos($imagePath, 'file'));
        $videothumb = config('global.base_url_short_videos') . $thumbFolder . '/' . $video->thumb_image;
    }

    // Move these calculations outside the if/else block so they're always available
$isFirst = $currentIndex <= 0;
$prev = !$isFirst ? $videos[$currentIndex - 1] : null;
$prevCat = $prev ? optional($prev->category)->site_url ?? 'category' : '#';

$isLast = $currentIndex >= count($videos) - 1;
$next = !$isLast ? $videos[$currentIndex + 1] : null;
$nextCat = $next ? optional($next->category)->site_url ?? 'category' : '#';
@endphp

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $videos[$currentIndex]->title . ' - Short Video-NMF News' ?? 'Short Video-NMF News' }}</title>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="video.other">
    <meta property="og:title" content="{{ $videos[$currentIndex]->title }}">
    <meta property="og:description"
        content="{{ $videos[$currentIndex]->description ?? 'Watch this short video on NMF News' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($videothumb ?? 'default-thumbnail.jpg') }}">
    <meta property="og:site_name" content="NMF News">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $videos[$currentIndex]->title }}">
    <meta name="twitter:description"
        content="{{ $videos[$currentIndex]->description ?? 'Watch this short video on NMF News' }}">
    <meta name="twitter:image" content="{{ asset($videothumb ?? 'default-thumbnail.jpg') }}">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3986924419662120"
        crossorigin="anonymous"></script>
    <style>
        :root {
            --vh: 100%;
        }

        .body-main {
            margin: 0;
            background: #0b1a2e;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        .shorts-wrapper {
            width: 100%;
            max-width: 600px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .shorts-wrapper {
            position: relative;
            display: flex;
            flex-direction: row;
            gap: 12px;
            align-items: center;
            justify-content: center;
        }

        .video-container {
            position: relative;
            width: 360px;
            height: 640px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            background: black;
        }

        .ad-slot-vertical {
            position: relative;
            width: 360px;
            height: 640px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .video-container video {
            opacity: 1;
            transition: opacity 0.4s ease-in-out;
        }

        .video-container video.fade-out {
            opacity: 0;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nav-wrapper {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 10;
        }

        .nav-btn {
            background-color: rgba(0, 0, 0, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }

        .nav-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .video-info {
            width: 100%;
            padding: 10px 12px 8px;
            background: linear-gradient(to top, rgba(0, 0, 0, 1), transparent);
            text-align: center;
            box-sizing: border-box;
        }

        .video-title {
            color: white;
            font-size: 18px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 6px;
            text-align: left;
        }

        .video-desc {
            font-size: 14px;
            color: #ccc;
        }

        .nav-column {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .nav-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .custom-play-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 0, 0, 0.7);
            border-radius: 50%;
            width: 64px;
            height: 64px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            z-index: 3;
            display: none;
        }

        .video-container.paused .custom-play-overlay {
            display: flex;
        }

        .video-actions {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-direction: column-reverse;
        }

        .act-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 28px;
            margin-top: -75px;
        }

        .action-btn {
            background: none;
            border: none;
            color: white;
            padding: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .action-btn:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }

        .video-overlay-bottom {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .reelshare-modal {
            display: none;
            position: absolute;
            z-index: 9999;
            left: 141px;
            top: 145px;
            width: 100%;
            height: 100%;
        }

        .reelshare-modal-content {
            background: #111;
            color: #fff;
            margin: 10% auto;
            padding: 10px;
            flex-direction: column;
            display: flex;
            gap: 15px;
            border-radius: 12px;
            width: 38px;
            text-align: center;
        }

        .reelshare-close {
            float: right;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 600px) {
            .body-main {
                height: calc(var(--vh, 1vh) * 100);
            }

            .reelshare-modal {
                display: none;
                position: absolute;
                z-index: 9999;
                left: 75px;
                top: 26%;
            }

            .video-info {
                margin-bottom: 0;
                padding-bottom: 10px;
                background: transparent;
            }

            /* Mobile-only Chrome on Android gets the extra bottom margin */
            @media (pointer: coarse) and (hover: none) {
                html.android-chrome .video-info {
                    margin-bottom: 57px;
                }
            }

            .shorts-wrapper .video-container,
            .shorts-wrapper .ad-slot-vertical {
                width: 100%;
                max-width: 100%;
                height: 100dvh;
                border-radius: 0;
            }

            .nav-column {
                display: flex;
                flex-direction: column;
                gap: 40px;
            }

            .act-wrap {
                position: absolute;
                gap: 28px;
                right: 16px;
                margin-top: 0px;
            }
        }
    </style>

    <!-- Google Tag Manager -->
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
        })(window, document, 'script', 'dataLayer', 'GTM-5BSHD2LX');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body class="body-main">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5BSHD2LX" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="shorts-wrapper">
        {{-- Show Ad instead of Video every 3rd video --}}
        @php
            $randomNumber = mt_rand(3, 5);
        @endphp
        @if (($currentIndex + 1) % $randomNumber == 0)
            <div class="ad-slot-vertical">
                <div class="ad-fallback-text"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #888; font-family: Arial, sans-serif;">
                    Advertisement
                </div>

                <ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px"
                    data-ad-client="ca-pub-3986924419662120" data-ad-slot="3792065060">
                </ins>

                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>

            </div>

            {{-- Navigation buttons for AD page --}}
            <div class="act-wrap" id="ad-navigation" style="display: none;">
                <a href="/">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffff" width="34"
                        height="34">
                        <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z" />
                    </svg>
                </a>

                {{-- Button Column for AD --}}
                <div class="nav-column">
                    {{-- Up Button --}}
                    <button class="nav-btn" {{ $isFirst ? 'disabled' : '' }}
                        onclick="navigateTo('{{ $prevCat }}', '{{ $prev->site_url ?? '' }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="white">
                            <path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.59 5.58L20 12l-8-8-8 8z" />
                        </svg>
                    </button>

                    {{-- Down Button --}}
                    <button class="nav-btn" {{ $isLast ? 'disabled' : '' }}
                        onclick="navigateTo('{{ $nextCat }}', '{{ $next->site_url ?? '' }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="white">
                            <path d="M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.59-5.58L4 12l8 8 8-8z" />
                        </svg>
                    </button>
                </div>
            </div>

            <script>
                setTimeout(function() {
                    var adNav = document.getElementById('ad-navigation');
                    if (adNav) {
                        // Use 'flex' because your .act-wrap class uses flexbox
                        adNav.style.display = 'flex';
                    }
                }, 5000); // 5000 milliseconds = 5 seconds
            </script>
        @else
            {{-- Video Box --}}
            <div class="video-container">
                <video id="videoPlayer" autoplay playsinline>
                    <source src="{{ $videoUrl }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="custom-play-overlay" onclick="togglePlay()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                        fill="white">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                </div>

                <!-- Modal -->
                <div id="reelshare-modal" class="reelshare-modal">
                    <div class="reelshare-modal-content">
                        <!-- WhatsApp Share -->
                        <button class="action-btn" onclick="shareOnWhatsApp()" aria-label="Share on WhatsApp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 32 32"
                                fill="green">
                                <path
                                    d="M16.001 2.002c-7.735 0-14 6.265-14 14 0 2.473.64 4.867 1.851 6.98L2.09 30l7.216-1.877a13.904 13.904 0 006.696 1.717h.001c7.735 0 14-6.265 14-14s-6.265-14-14.002-14zM16 28.002a11.937 11.937 0 01-6.09-1.672l-.436-.258-4.281 1.114 1.144-4.174-.285-.457a11.957 11.957 0 01-1.823-6.443c0-6.617 5.383-12 12-12 6.617 0 12 5.383 12 12s-5.383 12-12 12zm6.405-8.619c-.35-.175-2.078-1.027-2.4-1.145-.321-.117-.556-.175-.79.175s-.906 1.145-1.112 1.379c-.204.234-.408.263-.758.088-.35-.175-1.477-.544-2.814-1.736-1.04-.927-1.74-2.067-1.946-2.417s-.021-.525.154-.7c.158-.157.35-.408.525-.612.175-.204.233-.35.35-.583.117-.233.058-.438-.029-.612-.087-.175-.79-1.9-1.081-2.604-.284-.683-.574-.592-.79-.602l-.67-.012a1.294 1.294 0 00-.937.438c-.321.35-1.222 1.192-1.222 2.908s1.25 3.376 1.422 3.612c.175.233 2.458 3.748 5.956 5.255.833.358 1.482.572 1.988.73.837.266 1.6.229 2.203.14.672-.1 2.078-.85 2.371-1.672.292-.82.292-1.525.204-1.672-.087-.146-.32-.233-.67-.408z" />
                            </svg>
                        </button>

                        <!-- Facebook Share -->
                        <button class="action-btn" onclick="shareOnFacebook()" aria-label="Share on Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#1877F2"
                                class="bi bi-facebook" viewBox="0 0 16 16">
                                <path
                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                            </svg>
                        </button>

                        <!-- X (Twitter) Share -->
                        <button class="action-btn" onclick="shareOnX()" aria-label="Share on X">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                viewBox="0 0 512 512" fill="currentColor">
                                <path
                                    d="M389.2 48H466l-163 187.4L480 464h-123.7l-97.6-127.1L142.3 464H66l173.8-199.9L32 48h127.9l88.1 116.6L389.2 48zm-21.4 368h34.1L163.3 96h-36.6l241.1 320z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="video-overlay-bottom">
                    <div class="video-info">
                        <div class="video-title">{{ $videos[$currentIndex]->title }}</div>
                    </div>
                </div>
            </div>

            <div class="act-wrap">
                <a href="/">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffff" width="34"
                        height="34">
                        <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z" />
                    </svg>
                </a>

                <div class="video-actions">
                    <button class="action-btn" onclick="openReelShareModal()" aria-label="Copy Link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                            fill="white">
                            <path
                                d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7a2.5 2.5 0 000-.88l7.05-4.11A2.993 2.993 0 0018 7.91a3 3 0 10-2.83-2.12l-7.05 4.11a3.003 3.003 0 100 4.22l7.05 4.11A3 3 0 1018 16.08z">
                            </path>
                        </svg>
                    </button>

                    {{-- Mute / Unmute --}}
                    <button class="action-btn" onclick="toggleMute()" id="muteBtn" aria-label="Mute">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="white">
                            <path id="muteIconPath"
                                d="M4 9V15H8L13 20V4L8 9H4ZM16.5 12L19 14.5L20.5 13L18 10.5L20.5 8L19 6.5L16.5 9L14 6.5L12.5 8L15 10.5L12.5 13L14 14.5L16.5 12Z" />
                        </svg>
                    </button>
                </div>

                {{-- Button Column --}}
                <div class="nav-column">
                    {{-- Up Button (always visible) --}}
                    <button class="nav-btn" {{ $isFirst ? 'disabled' : '' }}
                        onclick="navigateTo('{{ $prevCat }}', '{{ $prev->site_url ?? '' }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="white">
                            <path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.59 5.58L20 12l-8-8-8 8z" />
                        </svg>
                    </button>

                    {{-- Down Button (always visible) --}}
                    <button class="nav-btn" {{ $isLast ? 'disabled' : '' }}
                        onclick="navigateTo('{{ $nextCat }}', '{{ $next->site_url ?? '' }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="white">
                            <path d="M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.59-5.58L4 12l8 8 8-8z" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <script>
        (function() {
            var ua = navigator.userAgent || "";
            var isAndroid = /Android/i.test(ua);
            var isChrome =
                /Chrome\/\d+/.test(ua) &&
                !/Edg|OPR|SamsungBrowser|UCBrowser|MiuiBrowser|DuckDuckGo|Brave|YaBrowser|CriOS/i.test(ua);

            if (isAndroid && isChrome) {
                document.documentElement.classList.add('android-chrome');
            }
        })();
    </script>

    <script>
        function openReelShareModal() {
            document.getElementById("reelshare-modal").style.display = "block";
        }

        function closeReelShareModal() {
            document.getElementById("reelshare-modal").style.display = "none";
        }

        // Close modal when clicking outside content
        document.getElementById("reelshare-modal").addEventListener("click", function(e) {
            if (e.target === this) {
                closeReelShareModal();
            }
        });
    </script>

    <script>
        function navigateTo(cat, name) {
            // For AD pages, we don't have a video element, so navigate directly
            @if (($currentIndex + 1) % 3 == 0)
                window.location.href = `/short-videos/${cat}/${name}`;
            @else
                const video = document.getElementById('videoPlayer');
                if (video) {
                    video.classList.add('fade-out');
                    setTimeout(() => {
                        window.location.href = `/short-videos/${cat}/${name}`;
                    }, 300);
                } else {
                    window.location.href = `/short-videos/${cat}/${name}`;
                }
            @endif
        }

        @if (($currentIndex + 1) % 3 != 0)
            const video = document.getElementById('videoPlayer');
            const container = document.querySelector('.video-container');
            const overlay = document.querySelector('.custom-play-overlay');

            // Click video to play/pause too
            video.addEventListener('click', togglePlay);

            function togglePlay() {
                if (video.paused) {
                    video.play();
                } else {
                    video.pause();
                }
            }

            video.addEventListener('play', () => {
                container.classList.remove('paused');
            });

            video.addEventListener('pause', () => {
                container.classList.add('paused');
            });

            // Optional: pause when video ends
            video.addEventListener('ended', () => {
                container.classList.add('paused');
            });
        @endif

        let startY = null;

        document.addEventListener('touchstart', function(e) {
            startY = e.touches[0].clientY;
        });

        document.addEventListener('touchend', function(e) {
            if (startY === null) return;

            const endY = e.changedTouches[0].clientY;
            const diffY = startY - endY;

            if (Math.abs(diffY) > 50) {
                if (diffY > 0) {
                    // Swipe Up → Next
                    @if ($currentIndex < count($videos) - 1)
                        navigateTo('{{ $nextCat }}', '{{ $next->site_url }}');
                    @endif
                } else {
                    // Swipe Down → Prev
                    @if ($currentIndex > 0)
                        navigateTo('{{ $prevCat }}', '{{ $prev->site_url }}');
                    @endif
                }
            }

            startY = null;
        });

        @if (($currentIndex + 1) % 3 != 0)
            function updateMuteIcon() {
                const video = document.getElementById('videoPlayer');
                const iconPath = document.getElementById('muteIconPath');

                const mutedPath =
                    "M16.5 12L19 14.5L20.5 13L18 10.5L20.5 8L19 6.5L16.5 9L14 6.5L12.5 8L15 10.5L12.5 13L14 14.5L16.5 12ZM4 9V15H8L13 20V4L8 9H4Z";
                const unmutedPath =
                    "M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.06c1.48-.74 2.5-2.26 2.5-4.03zm2.5 0c0 2.5-1.5 4.66-3.5 5.65v-2.18c1.16-.69 2-1.97 2-3.47s-.84-2.78-2-3.47V6.35c2 .99 3.5 3.15 3.5 5.65z";

                iconPath.setAttribute('d', video.muted ? mutedPath : unmutedPath);
            }

            function toggleMute() {
                const video = document.getElementById('videoPlayer');
                video.muted = !video.muted;
                updateMuteIcon();
            }
        @endif

        function shareOnWhatsApp() {
            const url = window.location.href;
            const message = encodeURIComponent("Check this video: " + url);
            window.open("https://wa.me/?text=" + message, "_blank");
        }

        function copyCurrentLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                //alert("Link copied!");
            });
        }

        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open("https://www.facebook.com/sharer/sharer.php?u=" + url, "_blank");
        }

        function shareOnX() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent("Check this video:");
            window.open("https://twitter.com/intent/tweet?text=" + text + "&url=" + url, "_blank");
        }

        // Ensure correct icon on page load
        @if (($currentIndex + 1) % 3 != 0)
            document.addEventListener('DOMContentLoaded', updateMuteIcon);
        @endif
    </script>

    <script>
        @if (($currentIndex + 1) % 3 != 0)
            ;
            document.addEventListener("DOMContentLoaded", function() {
                const video = document.getElementById("videoPlayer");
                const iconPath = document.getElementById("muteIconPath");

                if (!video || !iconPath) return;

                const mutedPath =
                    "M16.5 12L19 14.5L20.5 13L18 10.5L20.5 8L19 6.5L16.5 9L14 6.5L12.5 8L15 10.5L12.5 13L14 14.5L16.5 12ZM4 9V15H8L13 20V4L8 9H4Z";
                const unmutedPath =
                    "M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.06c1.48-.74 2.5-2.26 2.5-4.03zm2.5 0c0 2.5-1.5 4.66-3.5 5.65v-2.18c1.16-.69 2-1.97 2-3.47s-.84-2.78-2-3.47V6.35c2 .99 3.5 3.15 3.5 5.65z";

                function updateMuteIcon() {
                    iconPath.setAttribute("d", video.muted ? mutedPath : unmutedPath);
                }

                const ua = navigator.userAgent || "";
                const isIOS = /iPad|iPhone|iPod/.test(ua) && !window.MSStream;

                if (isIOS) {
                    // iOS Safari/Chrome → force muted autoplay
                    video.muted = true;
                    video.play().catch(err => {
                        console.log("iOS autoplay blocked:", err);
                    });
                } else {
                    // Android / Desktop → play normally (with sound)
                    video.muted = false;
                    video.play().catch(err => {
                        console.log("Autoplay failed:", err);
                    });
                }

                // Update icon immediately after setting mute state
                updateMuteIcon();
            });
        @endif
    </script>
</body>

</html>
