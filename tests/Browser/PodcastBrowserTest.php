<?php

namespace Tests\Browser;

use App\User;
use App\PodcastEpisode;
use App\Former\Member;

use Carbon\Carbon;
use Throwable;
use Laravel\Dusk\Browser;

class PodcastBrowserTest extends BrowserTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');

        User::factory()->create();
        $member = Member::factory()->create([
            'pseudo' => 'Alex RuTiPa'
        ]);
        $member2 = Member::factory()->create([
            'pseudo' => 'Arshes'
        ]);
        PodcastEpisode::factory()->create([
            'title' => 'First podcast',
            'slug' => 'first-podcast',
            'number' => 1,
            'duration_in_seconds' => 335, // 5min35
            'created_at' => Carbon::create(2020, 10, 1),
            'author_id' => $member
        ]);
        PodcastEpisode::factory()->create([
            'title' => 'Second podcast',
            'slug' => 'second-podcast',
            'number' => 2,
            'duration_in_seconds' => 539, // 8min59
            'created_at' => Carbon::create(2021, 2, 3),
            'author_id' => $member2
        ]);
    }

    /**
     * @test
     * @testdox Podcast - We can consult the podcast list, one podcast and RSS feed
     * Podcast - On peut consulter la liste des podcasts, un podcast et le flux RSS
     * @throws Throwable
     */
    public function podcastListAndRss()
    {
        $this->browse(fn(Browser $browser) => $browser->visit("/podcast")
            // List
            ->assertSee('First podcast')
            ->assertSee('01/10/2020')
            ->assertSee('Second podcast')
            ->assertSee('03/02/2021')
            // One podcast
            ->clickLink('First podcast')
            ->assertSee('First podcast')
            ->assertSee('le 01/10/2020')
            ->assertSee('Auteur : Alex RuTiPa')
            ->assertSee('Durée : 5:35')
        );
    }
}
