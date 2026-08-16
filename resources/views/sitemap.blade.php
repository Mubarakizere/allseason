{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemap.org/schemas/sitemap/0.9">
@foreach ($staticUrls as $item)
    <url>
        <loc>{{ $item['url'] }}</loc>
        <lastmod>{{ $item['lastmod'] }}</lastmod>
        <changefreq>{{ $item['changefreq'] }}</changefreq>
        <priority>{{ $item['priority'] }}</priority>
    </url>
@endforeach

@foreach ($menus as $menu)
    <url>
        <loc>{{ route('menu.item', $menu->id) }}</loc>
        <lastmod>{{ $menu->updated_at ? $menu->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach

@foreach ($blogs as $blog)
    <url>
        <loc>{{ route('blog.view', $blog->id) }}</loc>
        <lastmod>{{ $blog->updated_at ? $blog->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach

@foreach ($venues as $venue)
    <url>
        <loc>{{ route('venues.show', $venue->id) }}</loc>
        <lastmod>{{ $venue->updated_at ? $venue->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach

@foreach ($rooms as $room)
    <url>
        <loc>{{ route('rooms.show', $room->id) }}</loc>
        <lastmod>{{ $room->updated_at ? $room->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
</urlset>
