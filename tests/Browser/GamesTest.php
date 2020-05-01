<?php

namespace Tests\Browser;

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
        $this->browse(function (Browser $browser) {
            $browser->visit('/jeux')
                ->assertSee('Titre du Jeu')
                ->assertSee('Auteur(s)');
        });
    }
}
