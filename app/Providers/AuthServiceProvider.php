<?php

namespace App\Providers;

use App\Former\Game;
use App\PodcastEpisode;
use App\Policies\GamePolicy;
use App\Policies\PodcastEpisodePolicy;
use App\Policies\PreTestPolicy;
use App\Policies\WordPolicy;
use App\PreTest;
use App\Word;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Word::class => WordPolicy::class,
        Game::class => GamePolicy::class,
        PreTest::class => PreTestPolicy::class,
        PodcastEpisode::class => PodcastEpisodePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
