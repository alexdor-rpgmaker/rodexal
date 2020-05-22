<?php

namespace Tests\Feature;

use App\Former\Game;
use App\Former\Session;

/**
 * @testdox GamesRouter
 */
class GamesRouterTest extends FeatureTest
{
    private $currentSession;

    public function setUp(): void
    {
        parent::setUp();
        $this->resetDatabase();

        factory(Session::class)->create([
            'id_session' => 19,
            'nom_session' => 'Session 2019',
        ]);
        $this->currentSession = factory(Session::class)->create([
            'id_session' => 20,
            'nom_session' => 'Session 2020',
        ]);
        factory(Game::class)->create([
            'id_jeu' => 1,
            'support' => 'Software1',
            'id_session' => $this->currentSession,
        ]);
        factory(Game::class)->create([
            'id_jeu' => 2,
            'support' => 'Software2',
            'id_session' => $this->currentSession,
        ]);
    }

    // Index

    /**
     * @test
     * @testdox Index - List of games without query parameters
     * Liste des jeux sans paramètres d'URL
     */
    public function index_listOfGamesWithoutParameters()
    {
        $response = $this->get('/jeux');

        $response->assertOk();
        $response->assertViewHas('selectedSession', null);
        $response->assertViewHas('softwares', collect(['Software1', 'Software2']));

        $actualGameIds = $response['games']->map(fn($game) => $game->id_jeu)->toArray();
        $this->assertEquals([1, 2], $actualGameIds);

        $expectedSessions = Session::whereIn('id_session', [19, 20])->get();
        $response->assertViewHas('sessions', $expectedSessions);

        $response->assertViewHas('currentSession', $this->currentSession);
    }

    /**
     * @test
     * @testdox Index - List of games for a specific session
     * Liste des jeux pour une session donnée
     */
    public function index_listOfGamesOfASession()
    {
        $queryParameters = [
            'session_id' => '19',
        ];
        $response = $this->call('GET', '/jeux', $queryParameters);

        $response->assertOk();

        $response->assertViewHas('softwares', collect(['Software1', 'Software2']));

        $actualGamesCount = count($response['games']);
        $this->assertEquals(0, $actualGamesCount);

        $expectedSelectedSession = Session::find(19);
        $response->assertViewHas('selectedSession', $expectedSelectedSession);

        $expectedSessions = Session::whereIn('id_session', [19, 20])->get();
        $response->assertViewHas('sessions', $expectedSessions);

        $response->assertViewHas('currentSession', $this->currentSession);
    }

    // IndexVue

    /**
     * @test
     * @testdox IndexVue - List of games without query parameters
     * Liste des jeux sans paramètres d'URL
     */
    public function indexVue_listOfGamesWithoutParameters()
    {
        $response = $this->get('/jeux/vue');

        $response->assertOk();
        $response->assertViewHas('selectedSession', null);
        $response->assertViewHas('currentSession', $this->currentSession);
    }

    /**
     * @test
     * @testdox IndexVue - List of games for a specific session
     * Liste des jeux pour une session donnée
     */
    public function indexVue_listOfGamesOfASession()
    {
        $session = factory(Session::class)->create([
            'id_session' => 11,
            'nom_session' => 'Session 2011',
        ]);

        $queryParameters = [
            'session_id' => '11',
        ];
        $response = $this->call('GET', '/jeux/vue', $queryParameters);

        $response->assertOk();
        $response->assertViewHas('selectedSession', $session);
        $response->assertViewHas('currentSession');
    }

    /**
     * @test
     * @testdox IndexVue - List of games with a wrong session_id
     * Liste des jeux avec un mauvais paramètre de session
     */
    public function indexVue_listOfGamesWithWrongSessionId()
    {
        $queryParameters = [
            'session_id' => 'not-existing-session',
        ];
        $response = $this->call('GET', '/jeux/vue', $queryParameters);

        $response->assertOk();
        $response->assertViewHas('selectedSession', null);
        $response->assertViewHas('currentSession');
    }
}
