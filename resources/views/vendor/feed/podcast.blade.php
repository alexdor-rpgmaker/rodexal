<?=
    /* Using an echo tag here so the `<? ... ?>` won't get parsed as short tags */
    '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
{{-- Generated with php artisan vendor:publish --provider="Spatie\Feed\FeedServiceProvider" --tag="views" --}}
<!--suppress HtmlUnknownTag -->
<feed
        xmlns="http://www.w3.org/2005/Atom"
        xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:podcast="https://github.com/Podcastindex-org/podcast-namespace/blob/main/docs/1.0.md"
>
    @foreach($meta as $key => $metaItem)
        @if($key === 'link')
            <{{ $key }} href="{{ url($metaItem) }}"></{{ $key }}>
        @elseif($key === 'title')
            <{{ $key }}><![CDATA[{{ $metaItem }}]]></{{ $key }}>
        @else
            <{{ $key }}>{{ $metaItem }}</{{ $key }}>
        @endif
    @endforeach
    <pubDate>{{ \Carbon\Carbon::now()->toRfc3339String() }}</pubDate>
    <itunes:type>episodic</itunes:type>
    <itunes:subtitle>Parlons de création de jeu et du concours</itunes:subtitle>
    <itunes:summary>
        Dans ce podcast du concours des Alex d'or, nous évoquons la création de jeux vidéo sous RPG Maker et autres
        logiciels de création. C'est l'occasion de faire un point sur ce concours qui a plus de 20 ans !
    </itunes:summary>
    <itunes:author>Équipe des Alex d'or</itunes:author>
    <itunes:image href=""/>{{--TODO--}}
    <itunes:explicit>no</itunes:explicit>
    <itunes:keywords>podcast, jeux vidéo, rpg maker, gamedev, indiedev, unity, game maker</itunes:keywords>
    @foreach($items as $item)
        <entry>
            <title>Episode {{ $item->number }}: {{ $item->title }}</title>
            <link rel="alternate" href="{{ url($item->link) }}" />
            <guid>{{ url($item->link) }}</guid>
            <pubDate>{{ $item->publicationDate->toRfc3339String() }}</pubDate>
            <author>{{ $item->author }}</author>
            <enclosure
                    url="{{ $item->enclosureUrl }}"
                    length="{{ $item->enclosureLength }}"
                    type="{{ $item->enclosureType }}">
            </enclosure>
            <itunes:author>{{ $item->author }}</itunes:author>
            <itunes:episode>{{ $item->number }}</itunes:episode>
            <itunes:episodeType>full</itunes:episodeType>
            <itunes:duration>{{ $item->duration }}</itunes:duration>

            <itunes:summary>{{ $item->description }}</itunes:summary>
            <content:encoded>{{ $item->description }}</content:encoded>

            <itunes:subtitle>{{ $item->shortDescription }}</itunes:subtitle>
            <description>{{ $item->shortDescription }}</description>

            <podcast:person
                    email="{{ $item->podcastAuthor->email }}"
                    href="{{ $item->podcastAuthor->websiteUrl }}"
                    role="host"
            >{{ $item->podcastAuthor->name }}</podcast:person>
        </entry>
    @endforeach
</feed>
