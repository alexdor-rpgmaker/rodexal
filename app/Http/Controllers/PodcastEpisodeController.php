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
        $podcastEpisodes = PodcastEpisode::orderByDesc('created_at')->get();

        return view('podcast_episodes.index', [
            'podcastEpisodes' => $podcastEpisodes
        ]);
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
