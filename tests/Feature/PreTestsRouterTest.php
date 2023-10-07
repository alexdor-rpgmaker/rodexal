<?php

namespace Tests\Feature;

use App\Former\Game;
use App\Former\Juror;
use App\Former\Member;
use App\Former\Session;
use App\Former\TestSuite;
use App\Former\TestSuiteAssignedJuror;
use App\PreTest;
use App\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * @testdox PreTestsRouter
 */
class PreTestsRouterTest extends FeatureTestCase
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

    // Index

    /**
     * @test
     * @testdox Index - If no session is asked, then returns current session game and QCM
     * Si aucune session n'est demandée, alors renvoie le jeu et le QCM de la session actuelle
     */
    public function index_ifNoSessionIsAsked_thenReturnsCurrentSessionGameAndPreTest()
    {
        $preTest = PreTest::factory()->create([
            'game_id' => $this->currentSessionGame->id_jeu,
        ]);

        $response = $this->get("/qcm");

        $currentSessionGameId = $this->currentSessionGame->id_jeu;
        $response->assertOk()
            ->assertViewHas('session', $this->currentSession)
            ->assertViewHas('currentSession', $this->currentSession)
            ->assertViewHas('games.0', $this->currentSessionGame) // TODO: Fix this flacky test
            ->assertViewHas("preTestsByGameId.$currentSessionGameId.0", $preTest);
    }

    /**
     * @test
     * @testdox Index - If previous session is asked, then returns previous session game and QCM
     * Si on demande la session précédente, alors renvoie le jeu et le QCM de la session précédente
     */
    public function index_ifPreviousSessionIsAsked_thenReturnsPreviousSessionGameAndPreTest()
    {
        $preTest = PreTest::factory()->create([
            'game_id' => $this->previousSessionGame->id_jeu,
        ]);

        $sessionId = $this->previousSession->id_session;
        $response = $this->get("/qcm?session_id=$sessionId");

        $previousSessionGameId = $this->previousSessionGame->id_jeu;
        $response->assertOk()
            ->assertViewHas('session', $this->previousSession)
            ->assertViewHas('currentSession', $this->currentSession)
            ->assertViewHas('games.0', $this->previousSessionGame)
            ->assertViewHas("preTestsByGameId.$previousSessionGameId.0", $preTest);
    }

    /**
     * @test
     * @testdox Index - If game is deleted, then returns nothing
     * Si le jeu est supprimé, alors ne renvoie rien
     */
    public function index_ifGameIsDeleted_thenReturnsNoPreTests()
    {
        $this->currentSessionGame->update(['statut_jeu' => 'deleted']);

        PreTest::factory()->count(2)->create([
            'game_id' => $this->currentSessionGame->id_jeu,
        ]);

        $response = $this->get("/qcm");

        $response->assertOk()
            ->assertViewHas('games', new Collection());

        // Reset modifications
        $this->currentSessionGame->update(['statut_jeu' => 'registered']);
    }

    /**
     * @test
     * @testdox Index - If game is disqualified and current session step is 2, then returns this game
     * Si un jeu est disqualifié et que l'étape de la session actuelle est 2, alors renvoie ce jeu
     */
    public function index_ifGameIsDisqualifiedAndCurrentSessionStepIs2_thenReturnsThisGame()
    {
        $this->currentSession->update(['etape' => 2]);
        $this->currentSessionGame->update(['statut_jeu' => 'disqualified']);

        PreTest::factory()->count(2)->create([
            'game_id' => $this->currentSessionGame->id_jeu,
        ]);

        $response = $this->get("/qcm");

        $response->assertOk()
            ->assertViewHas('games.0', $this->currentSessionGame);

        // Reset modifications
        $this->currentSessionGame->update(['statut_jeu' => 'registered']);
    }

    // Show

    /**
     * @test
     * @testdox Show - We can see a filled QCM
     * On peut voir un QCM rempli
     */
    public function show_affichageQcm()
    {
        $game = Game::factory()->create([
            'id_jeu' => 789,
            'id_session' => $this->currentSession->id_session,
        ]);
        $preTest = PreTest::factory()->create([
            'game_id' => $game->id_jeu,
        ]);

        $response = $this->get("/qcm/$preTest->id");

        $response->assertOk();
    }

    // Create

    /**
     * @test
     * @testdox Create - A non-juror cannot access the new QCM form
     * On ne peut pas accéder au formulaire d'ajout de QCM si on n'est pas juré
     */
    public function create_ifNonJuror_thenForbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/qcm/creer');

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour créer un QCM !');
    }

    /**
     * @test
     * @testdox Create - Creating a QCM is forbidden if game is not assigned to the juror
     * On ne peut pas accéder au formulaire d'ajout de QCM si le jeu ne nous est pas attribué
     */
    public function create_ifGameNotAssigned_thenForbidden()
    {
        $user = User::factory()->jury()->create();

        $response = $this->actingAs($user)
            ->get('/qcm/creer?game_id=5');

        $response->assertForbidden()
            ->assertSeeText('Ce jeu ne vous est pas attribué !');
    }

    /**
     * @test
     * @testdox Create - If juror and game is assigned, then we can access new QCM form
     * On peut accéder au formulaire d'ajout de QCM si on est juré
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
            ->get('/qcm/creer?game_id=3');

        $response->assertOk();
    }

    // Store

    /**
     * @test
     * @testdox Store - A non-juror cannot store a new QCM
     * On ne peut pas enregistrer un QCM si on n'est pas juré
     */
    public function store_ifNonJuror_thenForbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/qcm');

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour créer un QCM !');
    }

    /**
     * @test
     * @testdox Store - Storing a QCM is forbidden if game is not assigned to the juror
     * On ne peut pas enregistrer un QCM pour un jeu qui ne nous est pas attribué
     */
    public function store_ifGameNotAssigned_thenForbidden()
    {
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
     * @test
     * @testdox Store - If parameters are missing then redirect
     * On est redirigés quand il manque des paramètres pour ajouter un QCM, si on est juré
     */
    public function store_ifParametersAreMissing_thenRedirect()
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
            ->post('/qcm', [
                'gameId' => 4,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('pre_tests', [
            'user_id' => $user->id,
            'game_id' => 4,
        ]);
    }

    /**
     * @test
     * @testdox Store - A juror can store a QCM
     * On peut créer un QCM si on est juré
     */
    public function store_ifEverythingIsOk_thenOk()
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
        $preTestSuite = TestSuite::factory()->create([
            'id_serie' => 125,
            'is_pre_test' => 1,
            'nom_serie' => 'Pré-Tests de 2021',
        ]);
        // Test suite that is not a pre-test, for creating an assignment
        TestSuite::factory()->create([
            'id_serie' => 126,
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
            'game_id' => $game->id_jeu,
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
     * @test
     * @testdox Edit - If user is not juror, then impossible to edit a QCM
     * On ne peut pas modifier un QCM si on est un membre régulier, créateur du QCM
     */
    public function edit_ifNotJuror_thenForbidden()
    {
        $user = User::factory()->create();
        $preTest = PreTest::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get("/qcm/{$preTest->id}/editer");

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour modifier un QCM !');
    }

    /**
     * @test
     * @testdox Edit - If user has not created QCM then forbidden
     * On ne peut pas modifier un QCM si on est un juré, non créateur du QCM
     */
    public function edit_ifUserHasNotCreatedQcm_thenForbidden()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create();

        $response = $this->actingAs($user)
            ->get("/qcm/{$preTest->id}/editer");

        $response->assertForbidden()
            ->assertSeeText("Vous devez être l'auteur du QCM pour pouvoir le modifier !");
    }

    /**
     * @test
     * @testdox Edit - If juror has created QCM, then it is possible to edit it
     * On peut modifier un QCM si on est le créateur du QCM
     */
    public function edit_ifJurorHasCreatedQcm_thenOk()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create([
            'user_id' => $user->id,
            'game_id' => 3,
        ]);

        $response = $this->actingAs($user)
            ->get("/qcm/{$preTest->id}/editer");

        $response->assertOk();
    }

    /**
     * @test
     * @testdox Edit - If user is admin, then it is possible to edit QCM of another
     * On peut modifier un QCM si on est admin et non créateur
     */
    public function edit_ifUserIsAdmin_thenOk()
    {
        $member = Member::factory()->create([
            'id_membre' => 789,
        ]);
        $user = User::factory()->admin()->create([
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
        $suite = TestSuite::factory()->create([
            'id_serie' => 127,
            'is_pre_test' => 1,
            'nom_serie' => 'Pré-Tests de 2021',
        ]);
        TestSuiteAssignedJuror::factory()->create([
            'id_jeu' => $game->id_jeu,
            'id_jury' => $juror->id_jury,
            'id_serie' => $suite->id_serie,
            'statut_jeu_jure' => 2,
        ]);
        $preTest = PreTest::factory()->create([
            'game_id' => $game->id_jeu,
            'user_id' => $member->id_membre,
        ]);

        $response = $this->actingAs($user)
            ->get("/qcm/{$preTest->id}/editer");

        $response->assertOk();
    }

    // Update

    /**
     * @test
     * @testdox Update - If user is not juror, then impossible to update a QCM
     * On ne peut pas modifier un QCM si on est un membre régulier, créateur du QCM
     */
    public function update_ifNotJuror_thenForbidden()
    {
        $user = User::factory()->create();
        $preTest = PreTest::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->put("/qcm/{$preTest->id}");

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour modifier un QCM !');
    }

    /**
     * @test
     * @testdox Update - If user has not created QCM then forbidden
     * On ne peut pas éditer un QCM si on est un juré, non créateur du QCM
     */
    public function update_ifUserHasNotCreatedQcm_thenForbidden()
    {
        $user = User::factory()->jury()->create();
        $preTest = PreTest::factory()->create();

        $response = $this->actingAs($user)
            ->put("/qcm/{$preTest->id}");

        $response->assertForbidden()
            ->assertSeeText("Vous devez être l'auteur du QCM pour pouvoir le modifier !");
    }

    /**
     * @test
     * @testdox Update - If fields are missing then redirect
     * On est redirigés quand il manque des paramètres pour mettre un QCM à jour, si on est admin
     */
    public function update_ifMissingFields_thenRedirect()
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
     * @test
     * @testdox Update - If everything is OK, QCM is updated
     * On peut mettre à jour un QCM si on est juré et créateur
     */
    public function update_ifEverythingIsOk_thenOk()
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
}
