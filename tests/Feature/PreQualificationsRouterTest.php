<?php

namespace Feature;

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
            'id_session' => 20,
        ]);
        Game::factory()->create([
            'id_jeu' => 20,
            'id_session' => $previousSession,
            'statut_jeu' => 'registered',
        ]);
        $currentSession = Session::factory()->create([
            'id_session' => 21,
        ]);
        Game::factory()->create([
            'id_jeu' => 21,
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

        $this->previousSession = Session::find(20);
        $this->previousSessionGame = Game::find(20);

        $this->currentSession = Session::find(21);
        $this->currentSessionGame = Game::find(21);

        // TODO: Comprendre pourquoi il y a une Session 22 alors que rien ne la crée dans ce fichier
        // (commenter ce mock pour ce faire)
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
    public function show_affichagePreQualification()
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
     * @testdox Show - We cannot see a filled qcm
     * On ne peut pas voir un qcm rempli
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
            'nom_serie' => 'Pré-Tests de 2021',
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
            'nom_serie' => 'Pré-Tests de 2021',
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
            'nom_serie' => 'Pré-Tests de 2021',
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
            'nom_serie' => 'Pré-Tests de 2021',
        ]);
        // Test suite that is not a pre-test, for creating an assignment
        TestSuite::factory()->create([
            'id_serie' => 127,
            'is_pre_test' => 0,
            'nom_serie' => 'Tests de 2021',
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
}
