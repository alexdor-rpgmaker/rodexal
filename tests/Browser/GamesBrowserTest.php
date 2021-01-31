<?php

namespace Tests\Browser;

use App\Former\Game;
use App\Former\Session;

use Throwable;
use Laravel\Dusk\Browser;

class GamesBrowserTest extends BrowserTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');

        $session = Session::factory()->create([
                'id_session' => 17,
        ]);
        // Create 51 games
        Game::factory()->count(51)->create([
            'support' => 'RPG Maker 2003',
            'id_session' => $session
        ]);
    }

    /**
     * @test
     * @testdox Games list - We can consult the subscribed games list
     * Liste des jeux - On peut consulter la liste des jeux inscrits
     * @throws Throwable
     */
    public function gamesList()
    {
        $gamesListUrls = ['/jeux', '/jeux/vue'];

        foreach ($gamesListUrls as $gamesListUrl) {
            // Log::debug($gamesListUrl);
            $this->browse(fn(Browser $browser) => $browser->visit($gamesListUrl)
                ->assertSee('Titre du Jeu')
                ->assertSee('Session')
                ->assertSee('Auteur(s)')
                ->assertSee('Nombre de jeux : 50 sur 51')

                ->select('#session', 'Session 2017-2018')
                ->select('#software', 'RPG Maker 2003')
                ->click('button[type="submit"]')
                ->pause(1000)
                ->assertSee('Nombre de jeux : 50 sur 51')

                // Pagination
                ->assertPresent('li.previous')
                ->assertPresent('li.previous.disabled')
                ->assertPresent('li.next')
                ->assertMissing('li.next.disabled')

                // Wrong research
                ->select('#session', 'Session 2017-2018')
                ->select('#software', 'RPG Maker 2003')
                ->type('#query', 'My search')
                ->check('#download-links')
                ->click('button[type="submit"]')
                ->pause(1000)
                ->assertSee('Nombre de jeux : 0')
            );
        }
    }

    /**
     * @test
     * @testdox Games list - Filter the list updates the URL
     * Liste des jeux - Filtrer la liste met à jour l'URL
     * @throws Throwable
     */
    public function filterGamesListUpdatesUrl()
    {
        $this->browse(fn(Browser $browser) => $browser->visit('/jeux')
            ->select('#session', '17')
            ->select('#software', 'RPG Maker 2003')
            ->type('#query', 'My search')
            ->check('#download-links')
            ->click('button[type="submit"]')
            ->assertQueryStringHas('q', 'My search')
            ->assertQueryStringHas('download_links', 'any')
            ->assertQueryStringHas('session_id', '17')
            ->assertQueryStringHas('software', 'RPG Maker 2003')
        );
    }
}
