{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

@foreach ($urls as $url)
<url>
    <loc>{{ $url['loc'] }}</loc>
    <lastmod>{{ \Carbon\Carbon::parse($url['lastmod'])->format('Y-m-d') }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>{{ $url['priority'] }}</priority>

    <thumbnail>{{ $url['thumbnail'] }}</thumbnail>
    <title><![CDATA[ {{ $url['title'] }} ]]></title>
    <description><![CDATA[ {{ $url['description'] }} ]]></description>
    <category><![CDATA[ {{ $url['category'] }} ]]></category>
    <published_at>{{ $url['published_at'] }}</published_at>
    <uploader>{{ $url['uploader'] }}</uploader>
</url>
@endforeach

</urlset>
