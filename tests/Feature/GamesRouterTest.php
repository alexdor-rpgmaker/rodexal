<?php

namespace Tests\Feature;

use App\Former\Session;

/**
 * @testdox GamesRouter
 */
class GamesRouterTest extends FeatureTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->resetDatabase();

        factory(Session::class)->create([
            'id_session' => 19,
            'nom_session' => 'Session 2019',
        ]);
    }

    // Index

    /**
     * @test
     * @testdox On peut accéder à la liste des jeux
     */
    public function listeDesJeuxSansParams()
    {
        $currentSession = factory(Session::class)->create([
            'id_session' => 20,
            'nom_session' => 'Session 2020',
        ]);

        $response = $this->get('/jeux/vue');

        $response->assertOk();
        $response->assertViewHas('selectedSession', null);
        $response->assertViewHas('currentSession', $currentSession);
    }

    /**
     * @test
     * @testdox On peut accéder à la liste des jeux pour une session en particulier
     */
    public function listeDesJeuxDUneSession()
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
     * @testdox On peut accéder à la liste des jeux en demandant une fausse session
     */
    public function listeDesJeuxAvecFauxParamSession()
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
