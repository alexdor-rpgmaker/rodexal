<?php

namespace Feature\PreTests;

use App\Former\Game;
use App\Former\Juror;
use App\Former\Member;
use App\Former\Session;
use App\Former\TestSuite;
use App\Former\TestSuiteAssignedJuror;
use App\PreTest;
use App\User;
use Tests\Feature\FeatureTestCase;

/**
 * @testdox PreQualificationsRouter
 */
class PreQualificationsRouterTest extends FeatureTestCase
{
    private Session $previousSession;
    private Session $currentSession;
    private Game $previousSessionGame;
    private Game $currentSessionGame;

    protected function refreshDatabase(): void
    {
        parent::refreshDatabase();

        $previousSession = Session::factory()->create([
            'id_session' => 22,
        ]);
        Game::factory()->create([
            'id_jeu' => 22,
            'id_session' => $previousSession,
            'statut_jeu' => 'registered',
        ]);
        $currentSession = Session::factory()->create([
            'id_session' => 23,
        ]);
        Game::factory()->create([
            'id_jeu' => 23,
            'id_session' => $currentSession,
            'statut_jeu' => 'registered',
        ]);
    }

    public static function setUpBeforeClass(): void
    {
        parent::refreshDatabaseOnNextSetup();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->previousSession = Session::find(22);
        $this->previousSessionGame = Game::find(22);

        $this->currentSession = Session::find(23);
        $this->currentSessionGame = Game::find(23);

        $this->instance(
            Session::class,
            $this->partialMock(
                Session::class,
                fn($mock) => $mock->shouldReceive('currentSession')
                    ->andReturn($this->currentSession)
            )
        );
    }

    // Show

    /**
     * @test
     * @testdox Show - We can see a filled pre-qualification
     * On peut voir une pré-qualification remplie
     */
    public function show_displayPreQualification()
    {
        $game = Game::factory()->create([
            'id_session' => $this->currentSession->id_session,
        ]);
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
            'game_id' => $game->id_jeu,
        ]);

        $response = $this->get("/pre_qualifications/$preTest->id");

        $response->assertOk();
    }

    /**
     * @test
     * @testdox Show - This URL is not accurate to read a filled QCM
     * Ce n'est pas la bonne URL pour voir un QCM rempli
     */
    public function show_ifQcm_thenNotFound()
    {
        $game = Game::factory()->create([
            'id_session' => $this->currentSession->id_session,
        ]);
        $preTest = PreTest::factory()->create([
            'type' => 'qcm',
            'game_id' => $game->id_jeu,
        ]);

        $response = $this->get("/pre_qualifications/$preTest->id");

        $response->assertNotFound();
    }

    // Create

    /**
     * @test
     * @testdox Create - A non-juror cannot access the new pre-qualification form
     * On ne peut pas accéder au formulaire d'ajout de pré-qualification si on n'est pas juré
     */
    public function create_ifNonJuror_thenForbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pre_qualifications/creer');

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour créer une pré-qualif/un QCM !');
    }

    /**
     * @test
     * @testdox Create - Creating a pre-qualification is forbidden if game is not assigned to the juror
     * On ne peut pas accéder au formulaire d'ajout de pré-qualification si le jeu ne nous est pas attribué
     */
    public function create_ifGameNotAssigned_thenForbidden()
    {
        $user = User::factory()->jury()->create();

        $response = $this->actingAs($user)
            ->get('/pre_qualifications/creer?game_id=5');

        $response->assertForbidden()
            ->assertSeeText('Ce jeu ne vous est pas attribué !');
    }

    /**
     * @test
     * @testdox Create - If juror and game is assigned, then we can access new pre-qualification form
     * On peut accéder au formulaire d'ajout de pré-qualification si on est juré
     */
    public function create_ifGameIsAssigned_thenOk()
    {
        $member = Member::factory()->create([
            'id_membre' => 456,
        ]);
        $user = User::factory()->jury()->create([
            'id' => $member->id_membre,
        ]);

        $game = Game::factory()->create([
            'id_jeu' => 3,
            'id_session' => $this->currentSession->id_session,
        ]);
        $juror = Juror::factory()->create([
            'id_membre' => $member->id_membre,
            'id_session' => $this->currentSession->id_session,
            'statut_jury' => 1,
        ]);
        $suite = TestSuite::factory()->create([
            'id_serie' => 123,
            'is_pre_test' => 1,
            'nom_serie' => 'Pré-Tests de 2023',
        ]);
        TestSuiteAssignedJuror::factory()->create([
            'id_jeu' => $game->id_jeu,
            'id_jury' => $juror->id_jury,
            'id_serie' => $suite->id_serie,
            'statut_jeu_jure' => 2,
        ]);

        $response = $this->actingAs($user)
            ->get('/pre_qualifications/creer?game_id=3');

        $response->assertOk();
    }

    // Store

    /**
     * @test
     * @testdox Store - A non-juror cannot store a new pre-qualification
     * On ne peut pas enregistrer une pré-qualification si on n'est pas juré
     */
    public function store_ifNonJuror_thenForbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/pre_qualifications');

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour créer une pré-qualif/un QCM !');
    }

    /**
     * @test
     * @testdox Store - Storing a pre-qualification is forbidden if game is not assigned to the juror
     * On ne peut pas enregistrer une pré-qualification pour un jeu qui ne nous est pas attribué
     */
    public function store_ifGameNotAssigned_thenForbidden()
    {
        $user = User::factory()->jury()->create();

        $response = $this->actingAs($user)
            ->post('/pre_qualifications', [
                'gameId' => 8,
            ]);

        $response->assertForbidden()
            ->assertSeeText('Ce jeu ne vous est pas attribué !');
        $this->assertDatabaseMissing('pre_tests', [
            'user_id' => $user->id,
            'game_id' => 8,
        ]);
    }

    /**
     * @test
     * @testdox Store - If parameters are missing then 422 error
     * On a une erreur 422 quand il manque des paramètres pour ajouter une pré-qualification, si on est juré
     */
    public function store_ifParametersAreMissing_thenUnprocessableEntityError()
    {
        $member = Member::factory()->create([
            'id_membre' => 567,
        ]);
        $user = User::factory()->jury()->create([
            'id' => $member->id_membre,
        ]);

        $game = Game::factory()->create([
            'id_jeu' => 4,
            'id_session' => $this->currentSession->id_session,
        ]);
        $juror = Juror::factory()->create([
            'id_membre' => $member->id_membre,
            'id_session' => $this->currentSession->id_session,
            'statut_jury' => 1,
        ]);
        $suite = TestSuite::factory()->create([
            'id_serie' => 124,
            'is_pre_test' => 1,
            'nom_serie' => 'Pré-Tests de 2023',
        ]);
        TestSuiteAssignedJuror::factory()->create([
            'id_jeu' => $game->id_jeu,
            'id_jury' => $juror->id_jury,
            'id_serie' => $suite->id_serie,
            'statut_jeu_jure' => 2,
        ]);

        $response = $this->actingAs($user)
            ->post('/pre_qualifications', [
                'gameId' => $game->id_jeu,
                // No other params
            ], [
                'Accept' => 'application/json',
            ]);

        $response->assertUnprocessable()
            ->assertJsonPath(
                "message",
                "Le champ Verdict est obligatoire. (and 14 more errors)"
            );
        $this->assertDatabaseMissing('pre_tests', [
            'user_id' => $user->id,
            'game_id' => 4,
        ]);
    }

    /**
     * @test
     * @testdox Store - If finalThought value is not one of the authorized ones then 422 error
     * On a une erreur 422 si le paramètre finalThought ne contient pas une des valeurs attendues
     */
    public function store_ifFinalThoughtParameterIsWrong_thenUnprocessableEntityError()
    {
        $member = Member::factory()->create([
            'id_membre' => 678,
        ]);
        $user = User::factory()->jury()->create([
            'id' => $member->id_membre,
        ]);

        $game = Game::factory()->create([
            'id_jeu' => 5,
            'id_session' => $this->currentSession->id_session,
        ]);
        $juror = Juror::factory()->create([
            'id_membre' => $member->id_membre,
            'id_session' => $this->currentSession->id_session,
            'statut_jury' => 1,
        ]);
        $suite = TestSuite::factory()->create([
            'id_serie' => 125,
            'is_pre_test' => 1,
            'nom_serie' => 'Pré-Tests de 2023',
        ]);
        TestSuiteAssignedJuror::factory()->create([
            'id_jeu' => $game->id_jeu,
            'id_jury' => $juror->id_jury,
            'id_serie' => $suite->id_serie,
            'statut_jeu_jure' => 2,
        ]);

        $unsavedPreTest = PreTest::factory()->make([
            'type' => 'pre-qualification',
            'game_id' => $game->id_jeu,
        ]);

        $response = $this->actingAs($user)
            ->post('/pre_qualifications', [
                'gameId' => $game->id_jeu,
                // Invalid finalThought value
                'finalThought' => '1',
                'finalThoughtExplanation' => null,
                'questionnaire' => $unsavedPreTest->questionnaire,
            ], [
                'Accept' => 'application/json',
            ]);

        $response->assertUnprocessable()
            ->assertJsonPath(
                "errors.finalThought.0",
                "L'élément sélectionné dans Verdict est invalide."
            );
        $this->assertDatabaseMissing('pre_tests', [
            'user_id' => $user->id,
            'game_id' => $game->id_jeu,
        ]);
    }

    /**
     * @test
     * @testdox Store - A juror can store a pre-qualification
     * On peut créer une pré-qualification si on est juré
     */
    public function store_ifEverythingIsOk_thenOk()
    {
        $member = Member::factory()->create([
            'id_membre' => 789,
        ]);
        $user = User::factory()->jury()->create([
            'id' => $member->id_membre,
        ]);

        $game = Game::factory()->create([
            'id_jeu' => 6,
            'id_session' => $this->currentSession->id_session,
        ]);
        $juror = Juror::factory()->create([
            'id_membre' => $member->id_membre,
            'id_session' => $this->currentSession->id_session,
            'statut_jury' => 1,
        ]);
        $preTestSuite = TestSuite::factory()->create([
            'id_serie' => 126,
            'is_pre_test' => 1,
            'nom_serie' => 'Pré-Tests de 2023',
        ]);
        // Test suite that is not a pre-test, for creating an assignment
        TestSuite::factory()->create([
            'id_serie' => 127,
            'is_pre_test' => 0,
            'nom_serie' => 'Tests de 2023',
        ]);
        TestSuiteAssignedJuror::factory()->create([
            'id_jeu' => $game->id_jeu,
            'id_jury' => $juror->id_jury,
            'id_serie' => $preTestSuite->id_serie,
            'statut_jeu_jure' => 2,
        ]);

        $unsavedPreTest = PreTest::factory()->make([
            'type' => 'pre-qualification',
            'game_id' => $game->id_jeu,
        ]);

        $response = $this->actingAs($user)
            ->post('/pre_qualifications', [
                'gameId' => $unsavedPreTest->game_id,
                'finalThought' => 'ok',
                'finalThoughtExplanation' => null,
                'questionnaire' => $unsavedPreTest->questionnaire,
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('pre_tests', [
            'user_id' => $user->id,
            'game_id' => $unsavedPreTest->game_id,
            'final_thought' => 'ok',
            'final_thought_explanation' => null,
            'type' => 'pre-qualification',
        ]);
    }

    // Edit

    /**
     * @test
     * @testdox Edit - This URL is not accurate to edit a filled QCM
     * Ce n'est pas la bonne URL pour modifier un QCM rempli
     */
    public function edit_ifQcm_thenNotFound()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create([
            'type' => 'qcm',
            'user_id' => $user->id,
            'game_id' => 3,
        ]);

        $response = $this->actingAs($user)
            ->get("/pre_qualifications/{$preTest->id}/editer");

        $response->assertNotFound();
    }

    /**
     * @test
     * @testdox Edit - If user is not juror, then impossible to edit a pre-qualification
     * On ne peut pas modifier une pré-qualification si on est un membre régulier, créateur de celle-ci
     */
    public function edit_ifNotJuror_thenForbidden()
    {
        $user = User::factory()->create();
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get("/pre_qualifications/{$preTest->id}/editer");

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour modifier une pré-qualif/un QCM !');
    }

    /**
     * @test
     * @testdox Edit - If user has not created pre-qualification then forbidden
     * On ne peut pas modifier une pré-qualification si on est un juré, non créateur de la pré-qualification
     */
    public function edit_ifUserHasNotCreatedQcm_thenForbidden()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
        ]);

        $response = $this->actingAs($user)
            ->get("/pre_qualifications/{$preTest->id}/editer");

        $response->assertForbidden()
            ->assertSeeText("Vous devez être l'auteur de la pré-qualif/du QCM pour pouvoir la/le modifier !");
    }

    /**
     * @test
     * @testdox Edit - If juror has created the pre-qualification, then it is possible to edit it
     * On peut modifier une pre-qualification si on est le créateur de la pre-qualification
     */
    public function edit_ifJurorHasCreatedQcm_thenOk()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
            'user_id' => $user->id,
            'game_id' => 3,
        ]);

        $response = $this->actingAs($user)
            ->get("/pre_qualifications/{$preTest->id}/editer");

        $response->assertOk();
    }

    /**
     * @test
     * @testdox Edit - If user is admin, then it is possible to edit the pre-qualification of another
     * On peut modifier une pré-qualification si on est admin et non créateur
     */
    public function edit_ifUserIsAdmin_thenOk()
    {
        $member = Member::factory()->create([
            'id_membre' => 890,
        ]);
        $user = User::factory()->admin()->create([
            'id' => $member->id_membre,
        ]);

        $game = Game::factory()->create([
            'id_jeu' => 7,
            'id_session' => $this->currentSession->id_session,
        ]);
        $juror = Juror::factory()->create([
            'id_membre' => $member->id_membre,
            'id_session' => $this->currentSession->id_session,
            'statut_jury' => 1,
        ]);
        $suite = TestSuite::factory()->create([
            'id_serie' => 128,
            'is_pre_test' => 1,
            'nom_serie' => 'Pré-Tests de 2023',
        ]);
        TestSuiteAssignedJuror::factory()->create([
            'id_jeu' => $game->id_jeu,
            'id_jury' => $juror->id_jury,
            'id_serie' => $suite->id_serie,
            'statut_jeu_jure' => 2,
        ]);
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
            'game_id' => $game->id_jeu,
            'user_id' => $member->id_membre,
        ]);

        $response = $this->actingAs($user)
            ->get("/pre_qualifications/{$preTest->id}/editer");

        $response->assertOk();
    }

    // Update

    /**
     * @test
     * @testdox Update - If user is not juror, then impossible to update a pre-qualification
     * On ne peut pas modifier une pre-qualification si on est un membre régulier, créateur de celle-ci
     */
    public function update_ifNotJuror_thenForbidden()
    {
        $user = User::factory()->create();
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->put("/pre_qualifications/{$preTest->id}");

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour modifier une pré-qualif/un QCM !');
    }

    /**
     * @test
     * @testdox Update - If user has not created the pre-qualification then forbidden
     * On ne peut pas modifier une pré-qualification si on est un juré, non créateur de la pré-qualification
     */
    public function update_ifUserHasNotCreatedQcm_thenForbidden()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
        ]);

        $response = $this->actingAs($user)
            ->put("/pre_qualifications/{$preTest->id}");

        $response->assertForbidden()
            ->assertSeeText("Vous devez être l'auteur de la pré-qualif/du QCM pour pouvoir la/le modifier !");
    }

    /**
     * @test
     * @testdox Update - If fields are missing when updating a pre-qualification then 422 error
     * On a une erreur 422 quand il manque des paramètres pour mettre une pré-qualification à jour, si on est admin
     */
    public function update_ifMissingFields_thenUnprocessableEntityError()
    {
        $user = User::factory()->admin()->create();
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
        ]);
        $unsavedPreTest = PreTest::factory()->make([
            'type' => 'pre-qualification',
        ]);

        $response = $this->actingAs($user)
            ->put("/pre_qualifications/{$preTest->id}", [
                'finalThoughtExplanation' => $unsavedPreTest->final_thought_explanation,
            ], [
                'Accept' => 'application/json',
            ]);

        $response->assertUnprocessable()
            ->assertJsonPath(
                "message",
                "Le champ questionnaire.abusive bugs.activated est obligatoire. (and 13 more errors)"
            );
        $this->assertDatabaseHas('pre_tests', [
            'final_thought_explanation' => $preTest->final_thought_explanation,
            'type' => 'pre-qualification',
        ]);
        $this->assertDatabaseMissing('pre_tests', [
            'final_thought_explanation' => $unsavedPreTest->final_thought_explanation,
            'type' => 'pre-qualification',
        ]);
    }

    /**
     * @test
     * @testdox Update - If everything is OK, the pre-qualification is updated
     * On peut mettre à jour une pré-qualification si on est juré et créateur
     */
    public function update_ifEverythingIsOk_thenOk()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create([
            'type' => 'pre-qualification',
            'user_id' => $user->id,
            'final_thought' => 'ok',
        ]);
        $newPreTest = PreTest::factory()->make([
            'type' => 'pre-qualification',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->put("/pre_qualifications/{$preTest->id}", [
                'gameId' => $newPreTest->game_id,
                'finalThought' => 'not-ok',
                'finalThoughtExplanation' => $newPreTest->final_thought_explanation,
                'questionnaire' => $newPreTest->questionnaire,
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('pre_tests', [
            'user_id' => $user->id,
            'game_id' => $preTest->game_id,
            'final_thought' => 'ok',
            'final_thought_explanation' => $newPreTest->final_thought_explanation,
            'type' => 'pre-qualification',
        ]);
    }
}
