<?php

namespace Tests\Feature;

use App\User;
use App\PreTest;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

/**
 * @testdox PreTestsRouter
 */
class PreTestsRouterTest extends FeatureTest
{
    // Show

    /**
     * @testdox On peut voir un QCM rempli
     */
    public function testAffichageQcm()
    {
        self::mockHttpClientShow();
        $preTest = PreTest::factory()->create();

        $response = $this->get("/qcm/$preTest->id");

        $response->assertOk();
    }

    // Create

    /**
     * @testdox On ne peut pas accéder au formulaire d'ajout de QCM si on n'est pas juré
     */
    public function testFormulaireNouveauQcmSiNonJury()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/qcm/creer');

        $response->assertForbidden();
    }

    /**
     * @testdox On ne peut pas accéder au formulaire d'ajout de QCM si le jeu ne nous est pas attribué
     */
    public function testFormulaireQcmPourJeuNonAttribue()
    {
        self::mockHttpClientCreate();
        $user = User::factory()->jury()->create();

        $response = $this->actingAs($user)
            ->get('/qcm/creer?game_id=5');

        $response->assertForbidden();
    }

    /**
     * @testdox On peut accéder au formulaire d'ajout de QCM si on est juré
     */
    public function testFormulaireNouveauQcmSiJury()
    {
        self::mockHttpClientCreate();
        $user = User::factory()->jury()->create();

        $response = $this->actingAs($user)
            ->get('/qcm/creer?game_id=3');

        $response->assertOk();
    }

    // Store

    /**
     * @testdox On ne peut pas valider un QCM si on n'est pas juré
     */
    public function testNouveauQcmSiNonJury()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/qcm');

        $response->assertForbidden();
    }

    /**
     * @testdox On ne peut pas écrire un QCM pour un jeu qui ne nous est pas attribué
     */
    public function testRedirectionSiEnregistrementAvecTestNonAttribue()
    {
        self::mockHttpClientCreate();
        $user = User::factory()->jury()->create();

        $response = $this->actingAs($user)
            ->post('/qcm', [
                'gameId' => 8,
            ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('pre_tests', [
            'user_id' => $user->id,
            'game_id' => 8,
        ]);
    }

    /**
     * @testdox On est redirigés quand il manque des paramètres pour ajouter un QCM, si on est juré
     */
    public function testRedirectionSiChampsManquantsSiJury()
    {
        self::mockHttpClientCreate();
        $user = User::factory()->jury()->create();

        $response = $this->actingAs($user)
            ->post('/qcm', [
                'gameId' => 3,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('pre_tests', [
            'user_id' => $user->id,
            'game_id' => 3,
        ]);
    }

    /**
     * @testdox On peut créer un QCM si on est juré
     */
    public function testNouveauQcmSiJury()
    {
        self::mockHttpClientCreate();
        $user = User::factory()->jury()->create();
        $unsavedPreTest = PreTest::factory()->make([
            'game_id' => 3,
        ]);

        $response = $this->actingAs($user)
            ->post('/qcm', [
                'gameId' => $unsavedPreTest->game_id,
                'finalThought' => true,
                'finalThoughtExplanation' => null,
                'questionnaire' => $unsavedPreTest->questionnaire,
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('pre_tests', [
            'user_id' => $user->id,
            'game_id' => $unsavedPreTest->game_id,
            'final_thought' => true,
            'final_thought_explanation' => null,
        ]);
    }

    // Edit

    /**
     * @testdox On ne peut pas éditer un QCM si on est un membre régulier, non créateur du QCM
     */
    public function testInterditDeModifierQcmSiMembreNormal()
    {
        $user = User::factory()->create();
        $preTest = PreTest::factory()->create();

        $response = $this->actingAs($user)
            ->get("/qcm/{$preTest->id}/editer");

        $response->assertForbidden();
    }

    /**
     * @testdox On ne peut pas éditer un QCM si on est un membre régulier, créateur du QCM
     */
    public function testInterditDeModifierQcmSiMembreNormalEtCreateur()
    {
        $user = User::factory()->create();
        $preTest = PreTest::factory()->create([
            'user_id' => $user->id,
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
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create();

        $response = $this->actingAs($user)
            ->get("/qcm/{$preTest->id}/editer");

        $response->assertForbidden();
    }

    /**
     * @testdox On peut éditer un QCM si on est le créateur du QCM
     */
    public function testModifierQcmSiJuryEtCreateur()
    {
        self::mockHttpClientShow();
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create([
            'user_id' => $user->id,
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
        self::mockHttpClientShow();
        $user = User::factory()->admin()->create();
        $preTest = PreTest::factory()->create();

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
        $user = User::factory()->create();
        $preTest = PreTest::factory()->create();

        $response = $this->actingAs($user)
            ->put("/qcm/{$preTest->id}");

        $response->assertForbidden();
    }

    /**
     * @testdox On est redirigés quand il manque des paramètres pour mettre un QCM à jour, si on est admin
     */
    public function testRedirectionSiChampsManquantsModificationSiAdmin()
    {
        $user = User::factory()->admin()->create();
        $preTest = PreTest::factory()->create();
        $unsavedPreTest = PreTest::factory()->make();

        $response = $this->actingAs($user)
            ->put("/qcm/{$preTest->id}", [
                'finalThoughtExplanation' => $unsavedPreTest->final_thought_explanation,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pre_tests', [
            'final_thought_explanation' => $preTest->final_thought_explanation,
        ]);
        $this->assertDatabaseMissing('pre_tests', [
            'final_thought_explanation' => $unsavedPreTest->final_thought_explanation,
        ]);
    }

    /**
     * @testdox On peut mettre à jour un QCM si on est juré et créateur
     */
    public function testModificationQcmSiJuryEtCreateur()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create([
            'user_id' => $user->id,
            'final_thought' => true,
        ]);
        $newPreTest = PreTest::factory()->make([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->put("/qcm/{$preTest->id}", [
                'gameId' => $newPreTest->game_id,
                'finalThought' => false,
                'finalThoughtExplanation' => $newPreTest->final_thought_explanation,
                'questionnaire' => $newPreTest->questionnaire,
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('pre_tests', [
            'user_id' => $user->id,
            'game_id' => $preTest->game_id,
            'final_thought' => true,
            'final_thought_explanation' => $newPreTest->final_thought_explanation,
        ]);
    }

    // Helper

    private function gameResponse()
    {
        return new Response(200, [], json_encode([
            'id' => 3,
            'title' => 'Legend of Lemidora',
        ]));
    }

    private function assignmentsResponse()
    {
        return new Response(200, [], json_encode([
            [
                'game_id' => 3,
                'pre_test' => true,
                'serie_locked' => false,
                'assignment_locked' => false,
            ],
        ]));
    }

    private function mockHttpClientShow()
    {
        $mock = new MockHandler([
            self::gameResponse(),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $this->app->instance(GuzzleClient::class, $client);
    }

    private function mockHttpClientCreate()
    {
        $mock = new MockHandler([
            self::assignmentsResponse(),
            self::gameResponse(),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $this->app->instance(GuzzleClient::class, $client);
    }

//    private function mockHttpClientStore()
//    {
//        $mock = new MockHandler([
//            self::assignmentsResponse(),
//            self::newAssignmentResponse(),
//        ]);
//        $handler = HandlerStack::create($mock);
//        $client = new GuzzleClient(['handler' => $handler]);
//        $this->app->instance(GuzzleClient::class, $client);
//    }
//
//    private function newAssignmentResponse()
//    {
//        $new_id = 14;
//        return new Response(200, [], $new_id);
//    }
}
