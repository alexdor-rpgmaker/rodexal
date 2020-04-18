<?php

namespace Tests\Feature;

use App\Former\Session;

/**
 * @testdox GamesRouter
 */
class GamesRouterTest extends FeatureTest
{
    // Index

    /**
     * @testdox On peut accéder à la liste des jeux
     */
    public function testListeDesJeuxSansParams()
    {
        $response = $this->get('/jeux');

        $response->assertOk();
        $response->assertViewHas('sessionId', null);
        $response->assertViewHas('sessionName', null);
    }

    /**
     * @testdox On peut accéder à la liste des jeux pour une session en particulier
     */
    public function testListeDesJeuxDUneSession()
    {
        factory(Session::class)->create([
            'id_session' => 11,
            'nom_session' => 'Session 2011',
        ]);

        $queryParameters = [
            'session_id' => '11',
        ];
        $response = $this->call('GET', '/jeux', $queryParameters);

        $response->assertOk();
        $response->assertViewHas('sessionId', '11');
        $response->assertViewHas('sessionName', 'Session 2011');
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
        $response->assertViewHas('sessionId', null);
        $response->assertViewHas('sessionName', null);
    }
}
