<?php

namespace Tests\Browser;

use App\User;
use App\Former\Game;
use App\Former\Member;
use App\Former\Session;

use Throwable;
use Carbon\Carbon;
use Laravel\Dusk\Browser;

class GamesBrowserTest extends BrowserTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');

        $session = Session::factory()->create([
            'id_session' => 17,
            'nom_session' => 'Session 2017',
            'etape' => 1,
            'date_cloture_inscriptions' => Carbon::tomorrow('Europe/Paris')
        ]);
        // Create 51 games
        Game::factory()->count(51)->create([
            'support' => 'RPG Maker 2003',
            'id_session' => $session
        ]);
    }

    /**
     * @test
     * @testdox Games list - We can consult the registered games list
     * Liste des jeux - On peut consulter la liste des jeux inscrits
     * @throws Throwable
     */
    public function gamesList_weCanConsultTheRegisteredGamesList()
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
    public function gamesList_filterTheListUpdatesTheUrl()
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

    /**
     * @test
     * @testdox Games registration - If visitor is not signed in, the form is not displayed
     * Inscription de jeu - Si le visiteur n'est pas connecté, le formulaire n'est pas affiché
     * @throws Throwable
     */
    public function gameRegistration_ifVisitorIsNotSignedIn_theFormIsNotDisplayed()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jeux/inscrire')
                ->assertSee('Vous devez être inscrit et connecté pour proposer un jeu')
                ->assertDontSee('Une présentation complète mais concise de votre jeu.');
        });
    }

    /**
     * @test
     * @testdox Games registration - If visitor is signed in, the form is displayed
     * Inscription de jeu - Si le visiteur est connecté, le formulaire est affiché
     * @throws Throwable
     */
    public function gameRegistration_ifVisitorIsSignedIn_theFormIsDisplayed()
    {
        Session::factory()->create([
            'id_session' => 21,
            'nom_session' => 'Session 2021',
            'etape' => 1,
            'date_cloture_inscriptions' => Carbon::tomorrow('Europe/Paris')
        ]);

        $member = Member::factory()->create([
            'pseudo' => 'Alex RuTiPa'
        ]);
        $user = User::factory()->create([
            'id' => $member->id_membre
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/jeux/inscrire')
                ->assertDontSee('Vous devez être inscrit et connecté pour proposer un jeu')
                ->assertSee('Une présentation complète mais concise de votre jeu.');

            $browser->type('title', 'Mon super jeu')
                ->radio('progression', 'full')
                ->select('#software-list', 'other')
                ->type('#other-software', 'Mon super logiciel')
                ->assertInputValue('software', 'Mon super logiciel')
                ->type('description', 'Bienvenue sur mon super jeu mdr. Téléchargez-le !!')
                ->click('button.submit');

            $browser->assertUrlIs(env('APP_URL') . '/jeux')
                ->assertSee('Jeu bien inscrit !')
                ->select('#session', '21')
                ->press('Rechercher')
                ->assertQueryStringHas('session_id', '21')
                ->assertSee('Mon super jeu')
                ->assertSee('Mon super logiciel')
                ->clickLink('Mon super jeu');

            // TODO : Check information on game page when it is on new website
            //$browser->assertUrlIs(env('FORMER_APP_URL') . '/')
            //    ->assertQueryStringHas('p', 'jeu')
            //    ->assertSee('Auteur(s) : Alex RuTiPa')
            //    ->assertSee('Mon super jeu')
            //    ->assertSee('Support : Mon super logiciel')
            //    ->assertSee('Description de l\'auteur Bienvenue sur mon super jeu mdr. Téléchargez-le !!');
        });
    }
}
