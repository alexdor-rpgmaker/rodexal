<?php

namespace App\Providers;

use App\Policies\WordPolicy;
use App\Policies\PreTestPolicy;
use App\Policies\PodcastEpisodePolicy;
use App\Word;
use App\PreTest;
use App\PodcastEpisode;

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
        $this->registerPolicies();

        //
    }
}
