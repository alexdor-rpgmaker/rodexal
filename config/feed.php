<?php

return [
    'feeds' => [
        'podcast' => [
            /*
             * Also possible:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => 'App\PodcastEpisode@getAllFeedItems',

            /*
             * The feed will be available on this url.
             */
            'url' => 'podcast/rss',
            'title' => 'Podcast des Alex d\'or',
            'description' => 'Podcast pour parler de l\'actualité du concours et des jeux',
            'language' => 'fr-FR',

            /*
             * The view that will render the feed.
             */
            'view' => 'feed::podcast',

            /*
             * The type to be used in the <link> tag
             */
            'type' => 'application/atom+xml',
        ],
    ],
];
