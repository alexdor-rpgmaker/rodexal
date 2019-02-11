<?php

namespace Tests\Feature;

use App\User;
use App\PreTest;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;

/**
 * @testdox PreTestsRouter
 */
class PreTestsRouterTest extends TestCase
{
    use RefreshDatabase;

    // Create

    /**
     * @testdox On ne peut pas accéder au formulaire d'ajout de QCM si on n'est pas juré
     */
    public function testFormulaireNouveauQcmSiNonJury()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                         ->get('/qcm/creer');

        $response->assertForbidden();
    }

    /**
     * @testdox On peut accéder au formulaire d'ajout de QCM si on est juré
     */
    public function testFormulaireNouveauQcmSiJury()
    {
        self::mockHttpClient();
        $user = factory(User::class)->states('jury')->create();

        $response = $this->actingAs($user)
                         ->get('/qcm/creer');

        $response->assertOk();
    }

    // Store

    /**
     * @testdox On ne peut pas valider un QCM si on n'est pas juré
     */
    public function testNouveauQcmSiNonJury()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                         ->post('/qcm');

        $response->assertForbidden();
    }

    /**
     * @testdox On est redirigés quand il manque des paramètres pour ajouter un QCM, si on est juré
     */
    public function testRedirectionSiChampsManquantsSiJury()
    {
        $user = factory(User::class)->states('jury')->create();

        $response = $this->actingAs($user)
                         ->post('/qcm', [
                             'user_id' => 2,
                             'game_id' => 8
                         ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('pre_tests', [
            'user_id' => 2,
            'game_id' => 8
        ]);
    }

    /**
     * @testdox On peut créer un QCM si on est juré
     */
    public function testNouveauQcmSiJury()
    {
        $user = factory(User::class)->states('jury')->create();
        $unsavedPreTest = factory(PreTest::class)->make();
        $response = $this->actingAs($user)
                         ->post('/qcm', [
            'gameId' => $unsavedPreTest->game_id,
            'finalThought' => true,
            'finalThoughtExplanation' => null,
            'questionnaire' => $unsavedPreTest->questionnaire
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('pre_tests', [
            'user_id' => $user->id,
            'game_id' => $unsavedPreTest->game_id,
            'final_thought' => true,
            'final_thought_explanation' => null
        ]);
    }

    // Edit

    /**
     * @testdox On ne peut pas éditer un QCM si on est un membre régulier, non créateur du QCM
     */
    public function testInterditDeModifierQcmSiMembreNormal()
    {
        $user = factory(User::class)->create();
        $preTest = factory(PreTest::class)->create();

        $response = $this->actingAs($user)
                         ->get("/qcm/{$preTest->id}/editer");

        $response->assertForbidden();
    }

    /**
     * @testdox On ne peut pas éditer un QCM si on est un membre régulier, créateur du QCM
     */
    public function testInterditDeModifierQcmSiMembreNormalEtCreateur()
    {
        $user = factory(User::class)->create();
        $preTest = factory(PreTest::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)
                         ->get("/qcm/{$preTest->id}/editer");

        $response->assertForbidden();
    }

    /**
     * @testdox On ne peut pas éditer un QCM si on est un juré, non créateur du QCM
     */
    public function testInterditDeModifierQcmSiJuryNonCreateur()
    {
        $user = factory(User::class)->states('jury')->create();
        $preTest = factory(PreTest::class)->create();

        $response = $this->actingAs($user)
                         ->get("/qcm/{$preTest->id}/editer");

        $response->assertForbidden();
    }

    /**
     * @testdox On peut éditer un QCM si on est le créateur du QCM
     */
    public function testModifierQcmSiJuryEtCreateur()
    {
        self::mockHttpClient();
        $user = factory(User::class)->states('jury')->create();
        $preTest = factory(PreTest::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)
                         ->get("/qcm/{$preTest->id}/editer");

        $response->assertOk();
    }

    /**
     * @testdox On peut éditer un QCM si on est admin et non créateur
     */
    public function testModifierQcmSiAdmin()
    {
        self::mockHttpClient();
        $user = factory(User::class)->states('admin')->create();
        $preTest = factory(PreTest::class)->create();

        $response = $this->actingAs($user)
                         ->get("/qcm/{$preTest->id}/editer");

        $response->assertOk();
    }

    // Update

    /**
     * @testdox On ne peut pas mettre à jour un QCM si on n'est pas juré ni créateur
     */
    public function testModificationQcmSiMembreNormalNonCreateur()
    {
        $user = factory(User::class)->create();
        $preTest = factory(PreTest::class)->create();

        $response = $this->actingAs($user)
                         ->put("/qcm/{$preTest->id}");

        $response->assertForbidden();
    }

    /**
     * @testdox On est redirigés quand il manque des paramètres pour mettre un QCM à jour, si on est admin
     */
    public function testRedirectionSiChampsManquantsModificationSiAdmin()
    {
        $user = factory(User::class)->states('admin')->create();
        $preTest = factory(PreTest::class)->create();
        $unsavedPreTest = factory(PreTest::class)->make();

        $response = $this->actingAs($user)
                         ->put("/qcm/{$preTest->id}", [
                            'finalThoughtExplanation' => $unsavedPreTest->final_thought_explanation
                         ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pre_tests', [
            'final_thought_explanation' => $preTest->final_thought_explanation
        ]);
        $this->assertDatabaseMissing('pre_tests', [
            'final_thought_explanation' => $unsavedPreTest->final_thought_explanation
        ]);
    }

    /**
     * @testdox On peut mettre à jour un QCM si on est juré et créateur
     */
    public function testModificationQcmSiJuryEtCreateur()
    {
        $user = factory(User::class)->states('jury')->create();
        $preTest = factory(PreTest::class)->create([
            'user_id' => $user->id
        ]);
        $newPreTest = factory(PreTest::class)->make([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)
                         ->put("/qcm/{$preTest->id}", [
                            'gameId' => $newPreTest->game_id,
                            'finalThought' => true,
                            'finalThoughtExplanation' => $newPreTest->final_thought_explanation,
                            'questionnaire' => $newPreTest->questionnaire
                         ]);

        $response->assertOk();
        $this->assertDatabaseHas('pre_tests', [
            'user_id' => $user->id,
            'game_id' => $preTest->game_id,
            'final_thought' => true,
            'final_thought_explanation' => $newPreTest->final_thought_explanation
        ]);
    }

    // Helper

    private function mockHttpClient($responseCode = 200) {
        $mock = new MockHandler([
            new Response($responseCode, [], json_encode([
                'id' => 3,
                'title' => 'Legend of Lemidora'
            ]))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $this->app->instance(GuzzleClient::class, $client);
    }
}
