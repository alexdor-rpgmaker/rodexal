<?php

namespace App\Http\Controllers;

use App\Former\Member;
use App\PodcastEpisode;

class PodcastEpisodeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PodcastEpisode::class);
    }

    public function index()
    {
        // TODO: Reactivate podcast:update cron task if new podcast episodes are published

        $podcastEpisodes = PodcastEpisode::orderByDesc('season')->orderByDesc('number')->get();

        $authorIds = $podcastEpisodes->pluck('author_id')->unique()->filter();
        $authors = Member::whereIn('id_membre', $authorIds)->get()->keyBy('id_membre');
        $podcastEpisodes->each(function ($episode) use ($authors) {
            $episode->author = $authors->get($episode->author_id);
        });

        return view('podcast_episodes.index', [
            'podcastEpisodes' => $podcastEpisodes
        ]);
    }

    public function help()
    {
        return view('podcast_episodes.help');
    }

    public function show(PodcastEpisode $podcastEpisode)
    {
        $author = Member::find($podcastEpisode->author_id);

        return view('podcast_episodes.show', [
            'podcastEpisode' => $podcastEpisode,
            'author' => $author
        ]);
    }
}
