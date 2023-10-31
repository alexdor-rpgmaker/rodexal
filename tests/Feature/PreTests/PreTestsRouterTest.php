<?php

namespace Feature\PreTests;

use App\Former\Game;
use App\Former\Session;
use App\PreTest;
use Illuminate\Database\Eloquent\Collection;
use Tests\Feature\FeatureTestCase;

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
            'type' => 'qcm',
            'game_id' => $this->currentSessionGame->id_jeu,
        ]);

        $response = $this->get("/pre_tests");

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
            'type' => 'qcm',
            'game_id' => $this->previousSessionGame->id_jeu,
        ]);

        $sessionId = $this->previousSession->id_session;
        $response = $this->get("/pre_tests?session_id=$sessionId");

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
            'type' => 'qcm',
            'game_id' => $this->currentSessionGame->id_jeu,
        ]);

        $response = $this->get("/pre_tests");

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
            'type' => 'qcm',
            'game_id' => $this->currentSessionGame->id_jeu,
        ]);

        $response = $this->get("/pre_tests");

        $response->assertOk()
            ->assertViewHas('games.0', $this->currentSessionGame);

        // Reset modifications
        $this->currentSessionGame->update(['statut_jeu' => 'registered']);
    }
}
