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
            // RSS feed
            ->back()
            ->clickLink('Flux RSS')
            ->assertSee('Podcast des Alex d&#039;or')
            ->assertSee('Episode 1: First podcast')
            ->assertSee('<guid>https://rodexal.test/podcast/first-podcast</guid>')
            ->assertSee('<author>Alex RuTiPa</author>')
            ->assertSee('<itunes:author>Alex RuTiPa</itunes:author>')
            ->assertSee('<itunes:episode>1</itunes:episode>')
            ->assertSee('<itunes:duration>5:35</itunes:duration>')
            ->assertSee('Episode 2: Second podcast')
            ->assertSee('<guid>https://rodexal.test/podcast/second-podcast</guid>')
            ->assertSee('<author>Arshes</author>')
            ->assertSee('<itunes:author>Arshes</itunes:author>')
            ->assertSee('<itunes:episode>2</itunes:episode>')
            ->assertSee('<itunes:duration>8:59</itunes:duration>')
        );
    }
}
