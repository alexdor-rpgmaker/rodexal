<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GamesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @testdox On peut consulter la liste des jeux du concours
     */
    public function testConsulterListeDesJeux()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jeux')
                ->assertSee('Nom du Jeu')
                ->assertSee('Auteur(s)');
        });
    }
}
