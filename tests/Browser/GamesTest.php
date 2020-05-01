<?php

namespace Tests\Browser;

use App\Former\Game;
use Laravel\Dusk\Browser;
use Throwable;

class GamesTest extends BrowserTest
{
    /**
     * @testdox On peut consulter la liste des jeux du concours
     * @throws Throwable
     */
    public function testConsulterListeDesJeux()
    {
        factory(Game::class)->create();
        $this->browse(function (Browser $browser) {
            $browser->visit('/jeux')
                ->assertSee('Titre du Jeu')
                ->assertSee('Auteur(s)');
        });
    }
}
