{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">

@foreach ($urls as $url)
<url>
    <loc>{{ $url['loc'] }}</loc>
    <lastmod>{{ \Carbon\Carbon::parse($url['lastmod'])->format('Y-m-d') }}</lastmod>

    <video:video>
        <video:thumbnail_loc><![CDATA[ {{ $url['thumbnail'] }} ]]></video:thumbnail_loc>
        <video:title><![CDATA[ {{ $url['title'] }} ]]></video:title>
        <video:description><![CDATA[ {{ $url['description'] }} ]]></video:description>
        
        @if(!empty($url['content']))
        <video:content_loc><![CDATA[ {{ $url['content'] }} ]]></video:content_loc>
        @endif

        @if(!empty($url['duration']))
        <video:duration>{{ $url['duration'] }}</video:duration>
        @endif

        <video:publication_date>{{ $url['publication_date'] }}</video:publication_date>

        <video:category><![CDATA[ {{ $url['category'] }} ]]></video:category>
        <video:uploader><![CDATA[ {{ $url['uploader'] }} ]]></video:uploader>
        
        <video:family_friendly>yes</video:family_friendly>
        
    </video:video>

</url>
@endforeach

</urlset>