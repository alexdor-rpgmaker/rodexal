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
     * @testdox On peut accéder à la liste des jeux
     */
    public function testListeDesJeuxSansParams()
    {
        $currentSession = factory(Session::class)->create([
            'id_session' => 20,
            'nom_session' => 'Session 2020',
        ]);

        $response = $this->get('/jeux');

        $response->assertOk();
        $response->assertViewHas('selectedSession', null);
        $response->assertViewHas('currentSession', $currentSession);
    }

    /**
     * @testdox On peut accéder à la liste des jeux pour une session en particulier
     */
    public function testListeDesJeuxDUneSession()
    {
        $session = factory(Session::class)->create([
            'id_session' => 11,
            'nom_session' => 'Session 2011',
        ]);

        $queryParameters = [
            'session_id' => '11',
        ];
        $response = $this->call('GET', '/jeux', $queryParameters);

        $response->assertOk();
        $response->assertViewHas('selectedSession', $session);
        $response->assertViewHas('currentSession');
    }

    /**
     * @testdox On peut accéder à la liste des jeux en demandant une fausse session
     */
    public function testListeDesJeuxAvecFauxParamSession()
    {
        $queryParameters = [
            'session_id' => 'not-existing-session',
        ];
        $response = $this->call('GET', '/jeux', $queryParameters);

        $response->assertOk();
        $response->assertViewHas('selectedSession', null);
        $response->assertViewHas('currentSession');
    }
}
