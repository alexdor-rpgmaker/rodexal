<?php

namespace Tests\Browser;

use App\User;

use Laravel\Dusk\Browser;
use Facebook\WebDriver\WebDriverBy;
use Throwable;

class QcmTest extends BrowserTest
{
    /**
     * @testdox On est redirigés sur l'ancien site si on essaye de créer un QCM en n'étant pas connecté
     * @throws Throwable
     */
    public function testRedirectionSiCreerQcmNonConnecte()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/qcm/creer?game_id=937')
                ->assertUrlIs(env('FORMER_APP_URL') . '/')
                ->assertSee('Pour voir cette page, vous devez être inscrit !');
        });
    }

    /**
     * @testdox On peut créer et modifier un QCM si on est juré
     * @throws Throwable
     */
    public function testCreerEtModifierQcmSiJury()
    {
        $user = factory(User::class)->state('jury')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/qcm/creer?game_id=937')
                ->assertPathBeginsWith('/qcm/creer')
                ->assertDontSee('Ce jeu ne vous est pas attribué !')
                ->assertSee('Remplir un QCM pour le jeu');

            $browser->waitFor('#notLaunchable');

            $elements = $browser->driver->findElements(WebDriverBy::className('form-check-input'));
            $this->assertCount(10, $elements);

            $browser->check('notAutonomous')
                ->type('#explanation-notAutonomous', 'Ce jeu nécessite les RTP')
                ->check('notLaunchable')
                ->type('#explanation-notLaunchable', 'Ce jeu ne se lance pas')
                ->check('blockingBug')
                ->type('#explanation-blockingBug', 'Un buisson me bloque la route')
                ->check('severalBugs')
                ->type('#explanation-severalBugs', 'Je ne peux pas faire un pas sans un glitch')
                ->check('spellingMistakes')
                ->type('#explanation-spellingMistakes', 'A ce stade il faut acheter un dico')
                ->check('tooHard')
                ->type('#explanation-tooHard', 'Impossible de finir le premier niveau')
                ->check('tooShort')
                ->type('#explanation-tooShort', 'Même pas 5 min de jeu ?!')
                ->check('unplayableAlone')
                ->type('#explanation-unplayableAlone', "J'ai eu besoin de mes 3 soeurs pour y jouer...")
                ->radio('finalThought', 'false')
                ->type('#finalThoughtPrecision', "Ce jeu était vraiment [u]nul[/u]. Mais bon courage pour la suite ! :)")
                ->click('button.submit')
                ->waitUntilMissing('#pre-tests-form')
                ->assertUrlIs(env('FORMER_APP_URL') . '/')
                ->assertQueryStringHas('p', 'mes-tests');

            $browser->visit('/qcm/1')
                ->assertSourceHas('Ce jeu était vraiment <u>nul</u>. Mais bon courage pour la suite ! :)')
                ->click('a.modifier-pre-test')
                ->assertSee('Modifier le QCM du jeu');

            $browser->waitFor('#notLaunchable')
                ->assertInputValue('#explanation-notLaunchable', 'Ce jeu ne se lance pas')
                ->type('#explanation-notLaunchable', 'Ce jeu se lance finalemnet')
                ->click('button.submit')
                ->waitUntilMissing('#pre-tests-form')
                ->assertUrlIs(env('FORMER_APP_URL') . '/')
                ->assertQueryStringHas('p', 'mes-tests');
        });
    }
}
