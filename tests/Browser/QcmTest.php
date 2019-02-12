<?php

namespace Tests\Browser;

use App\User;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QcmTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @testdox On ne peut pas accéder à la page de création de QCM si on n'est pas connecté
     */
    public function testErreurSiCreerQcmNonConnecte()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/qcm/creer?game_id=937')
                    ->assertSee('403');
        });
    }

    /**
     * @testdox On peut créer et modifier un QCM si on est juré
     */
    public function testCreerEtModifierQcmSiJury()
    {
        $user = factory(User::class)->state('jury')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/qcm/creer?game_id=937')
                ->assertSee('Remplir un QCM pour le jeu');

            $browser->waitFor('#notLaunchable')
                ->check('notLaunchable')
                ->type('#explanation-notLaunchable', 'Ce jeu plante au démarrage')
                ->radio('finalThought', 'true')
                ->click('button.submit')
                ->waitUntilMissing('#pre-tests-form')
                ->assertUrlIs(env('FORMER_APP_URL').'/')
                ->assertQueryStringHas('p', 'mes-tests');

            $browser->visit('/qcm/1/editer')
                ->assertSee('Modifier le QCM du jeu');

            $browser->waitFor('#notLaunchable')
                ->assertInputValue('#explanation-notLaunchable', 'Ce jeu plante au démarrage')
                ->type('#explanation-notLaunchable', 'Ce jeu ne plante plus au démarrage')
                ->radio('finalThought', 'false')
                ->click('button.submit')
                ->waitUntilMissing('#pre-tests-form')
                ->assertUrlIs(env('FORMER_APP_URL').'/')
                ->assertQueryStringHas('p', 'mes-tests');
        });
    }
}
