<?php

namespace Tests\Browser;

use App\User;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DictionaryTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @testdox On est redirigés sur l'ancien site si on essaye de créer un mot du dictionnaire en n'étant pas connecté
     */
    public function testRedirectionSiRemplirDictionnaireNonConnecte()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dictionnaire/creer')
                ->assertUrlIs(env('FORMER_APP_URL') . '/')
                ->assertSee('Pour voir cette page, vous devez être inscrit !');
        });
    }

    /**
     * @testdox On peut ajouter et modifier un mot du dictionnaire si on est admin
     */
    public function testRemplirDictionnaireSiConnecte()
    {

        $user = factory(User::class)->state('admin')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/dictionnaire/creer')
                ->assertDontSee('403')
                ->assertSee('Ajouter un mot au dictionnaire');

            $browser->type('#word-label', 'Gameplay')
                ->type('#word-description', 'Un jeu sans <gameplay> c\'est un jeu [b]sans saveur[/b].')
                ->click('button.submit')
                ->assertUrlIs(env('APP_URL') . '/dictionnaire')
                ->assertSee('Mot bien ajouté au dictionnaire !')
                ->assertSeeIn('#gameplay .card-body', 'Un jeu sans <gameplay> c\'est un jeu sans saveur.')
                ->assertSourceHas('un jeu <b>sans saveur</b>.');

            $browser->click('#gameplay .edit')
                ->assertInputValue('#word-label', 'Gameplay')
                ->assertInputValue('#word-description', 'Un jeu sans <gameplay> c\'est un jeu [b]sans saveur[/b].')
                ->type('#word-label', 'Roleplay')
                ->type('#word-description', 'Un jeu sans <roleplay> c\'est un jeu [b]sans bonheur[/b].')
                ->click('button.submit')
                ->assertUrlIs(env('APP_URL') . '/dictionnaire')
                ->assertSee('Mot du dictionnaire bien modifié !')
                ->assertSeeIn('#gameplay .card-body', 'Un jeu sans <roleplay> c\'est un jeu sans bonheur.')
                ->assertSourceHas('un jeu <b>sans bonheur</b>.');

            $browser->click('#gameplay button.delete')
                ->assertUrlIs(env('APP_URL') . '/dictionnaire')
                ->assertSee('Mot bien supprimé...')
                ->assertDontSee('Un jeu sans <roleplay> c\'est un jeu sans bonheur.');
        });
    }
}
