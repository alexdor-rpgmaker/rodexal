<?php

namespace App\Console\Commands;

use App\PodcastEpisode;

use Feeds;
use Illuminate\Console\Command;

class PodcastUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'podcast:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the Podcast RSS file from Anchor and updates the Podcast Episodes table in database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $items = Feeds::make(PodcastEpisode::PODCAST_FEED_URL)->get_items();

        foreach ($items as $item) {
            // http://simplepie.org/api/class-SimplePie_Item.html

            $SIMPLEPIE_NAMESPACE_ITUNES = "http://www.itunes.com/dtds/podcast-1.0.dtd";

            // echo $item->get_link() . "\n";
            // echo $item->get_item_tags($SIMPLEPIE_NAMESPACE_ITUNES, 'image')[0]['attribs']['']['href'] . "\n";

            $duration = (int)$item->get_item_tags($SIMPLEPIE_NAMESPACE_ITUNES, 'duration')[0]['data'];
            $seasonNumber = (int)$item->get_item_tags($SIMPLEPIE_NAMESPACE_ITUNES, 'season')[0]['data'];
            $episodeNumber = (int)$item->get_item_tags($SIMPLEPIE_NAMESPACE_ITUNES, 'episode')[0]['data'];

            // TODO: Update for each session, or make it variable somehow
            if($seasonNumber == 1) {
                // Session 2021
                $authorId = 588; // Parkko
            } else if($seasonNumber == 2) {
                // Session 2022
                $authorId = 1133; // Solarius
            }

            PodcastEpisode::updateOrCreate(
                [
                    'id' => $item->get_id(),
                ],
                [
                    'title' => strip_tags($item->get_title()),
                    'description' => strip_tags($item->get_description(), 100),
                    'created_at' => $item->get_date('Y-m-d'),
                    'audio_url' => $item->get_enclosure()->get_link(),
                    'season' => $seasonNumber,
                    'number' => $episodeNumber,
                    'duration_in_seconds' => $duration,
                    'author_id' => $authorId,
                    'poster_id' => $authorId,
                ]
            );
        }

        return 0;
    }
}
