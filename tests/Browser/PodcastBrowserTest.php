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
            'title' => 'Episode 1 : Game of season 1',
            'slug' => 'episode-1-game-season-1',
            'season' => 1,
            'number' => 1,
            'duration_in_seconds' => 335, // 5min35
            'created_at' => Carbon::create(2021, 10, 1),
            'author_id' => $member
        ]);
        PodcastEpisode::factory()->create([
            'title' => 'Episode 2 : Game of season 1',
            'slug' => 'episode-2-game-season-1',
            'season' => 1,
            'number' => 2,
            'duration_in_seconds' => 539, // 8min59
            'created_at' => Carbon::create(2021, 2, 3),
            'author_id' => $member
        ]);
        PodcastEpisode::factory()->create([
            'title' => 'Episode 1 : Game of season 2',
            'slug' => 'episode-1-game-season-2',
            'season' => 2,
            'number' => 2,
            'duration_in_seconds' => 100, // 1min40
            'created_at' => Carbon::create(2022, 5, 15),
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
            ->assertSee('Episode 1 : Game of season 1')
            ->assertSee('1 (2021)')
            ->assertSee('01/10/2021')
            ->assertSee('Episode 2 : Game of season 1')
            ->assertSee('03/02/2021')
            ->assertSee('Episode 1 : Game of season 2')
            ->assertSee('2 (2022)')
            ->assertSee('15/05/2022')
            // One podcast
            ->clickLink('Episode 1 : Game of season 1')
            ->assertSee('Episode 1 : Game of season 1')
            ->assertSee('le 01/10/2021')
            ->assertSee('Saison 1 (2021)')
            ->assertSee('Auteur : Alex RuTiPa')
            ->assertSee('Durée : 5:35')
            // Other podcast
            ->back()
            ->clickLink('Episode 1 : Game of season 2')
            ->assertSee('Episode 1 : Game of season 2')
            ->assertSee('le 15/05/2022')
            ->assertSee('Saison 2 (2022)')
            ->assertSee('Auteur : Arshes')
            ->assertSee('Durée : 1:40')
        );
    }
}
