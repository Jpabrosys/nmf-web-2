<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:media="http://search.yahoo.com/mrss/" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>NMF News Feed</title>
        <link>https://newsnmf.com</link>
        <atom:link href="{{ url('/feed', [], true) }}" rel="self" type="application/rss+xml" />
        <description>Latest news articles from NMF News</description>
        <language>hi-IN</language>
        <pubDate>{{ now()->toRfc2822String() }}</pubDate>
        <ttl>30</ttl>
        <image>
            <url>{{ asset('frontend/images/logo.png', true) }}</url>
            <title>NMF News Feed</title>
            <link>https://newsnmf.com</link>
        </image>

        @foreach ($blogs as $blog)
            @if ($blog->category)
                @php
                    // 1. Generate Article Link
                    $postLink = url($blog->category->site_url . '/' . $blog->site_url, [], true);

                    // 2. IMAGE LOGIC (Fixed for your Database)
                    $imageUrl = null;

                    // Check if the 'images' relationship is loaded and has data
                    if ($blog->images) {
                        // If it's a collection (hasMany), get the first item. If it's a single model (hasOne), use it directly.
                        $mediaFile =
                            $blog->images instanceof \Illuminate\Database\Eloquent\Collection
                                ? $blog->images->first()
                                : $blog->images;

                        // Your files table uses 'file_name'. The path in screenshot indicates 'public/file/'
                        if ($mediaFile && !empty($mediaFile->file_name)) {
                            $imageUrl = asset('file/' . $mediaFile->file_name, true);
                        }
                    }

                    // Fallback: Check if there's a thumb_image or other column if the relationship fails
if (!$imageUrl && !empty($blog->thumb_images)) {
    // Assuming thumb_images might be a direct filename in some cases
    $imageUrl = asset('file/' . $blog->thumb_images, true);
}

// 3. Author Logic
$authorName = 'NMF News';
                    if (!empty($blog->authorUser)) {
                        $authorName = $blog->authorUser->name;
                    } elseif (!empty($blog->author) && is_numeric($blog->author)) {
                        $user = \App\Models\User::find($blog->author);
                        if ($user) {
                            $authorName = $user->name;
                        }
                    }
                @endphp

                <item>
                    <title>
                        <![CDATA[{!! $blog->name !!}]]>
                    </title>
                    <link>{{ $postLink }}</link>
                    <description>
                        <![CDATA[{!! \Illuminate\Support\Str::limit(strip_tags($blog->sort_description), 200) !!}]]>
                    </description>

                    <content:encoded>
                        <![CDATA[
            <!doctype html>
            <html lang="hi">
              <head><meta charset="utf-8"></head>
              <body>
                {!! $blog->description !!}
              </body>
            </html>
          ]]>
                    </content:encoded>

                    {{-- Feature Image --}}
                    @if ($imageUrl)
                        <media:content url="{{ $imageUrl }}" medium="image" width="1200" height="675">
                            <media:title type="html">
                                <![CDATA[{!! $blog->name !!}]]>
                            </media:title>
                        </media:content>
                    @endif

                    <dc:creator>
                        <![CDATA[{{ $authorName }}]]>
                    </dc:creator>
                    <category>
                        <![CDATA[{!! $blog->category->name !!}]]>
                    </category>
                    <pubDate>{{ \Carbon\Carbon::parse($blog->created_at)->toRfc2822String() }}</pubDate>
                    <guid isPermaLink="true">{{ $postLink }}</guid>
                </item>
            @endif
        @endforeach

    </channel>
</rss>
