<?php

namespace Tests\Feature;

use App\Former\Game;
use App\Former\Session;
use App\Former\Participant;

use Tests\TestCase;

/**
 * @testdox GameApiRouter
 */
class GameApiRouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

    /**
     * @testdox On peut accéder à la liste des jeux via l'API
     */
    public function testGamesApiIndex()
    {
        $session = factory(Session::class)->create([
            'id_session' => 1,
            'nom_session' => 'Session 2001',
        ]);
        $firstGame = factory(Game::class)->create([
            'id_session' => $session->id_session,
            'statut_jeu' => '1',
            'nom_jeu' => 'Fake Game',
            'genre_jeu' => 'Adventure game',
            'support' => 'RPG Maker 2003',
            'theme' => 'Faking',
            'duree' => '2:00',
            'poids' => '130',
            'site_officiel' => 'https://fake-game.com',
            'description_jeu' => 'Just a sample game in order to test',
            'groupe' => 'Faking Games Software',
            'logo' => 'fake-game-logo.png',
            'logo_distant' => 'https://fake-game.com/logo.png',
            'date_inscription' => '2020-04-01 12:00:00',
        ]);
        factory(Participant::class)->create([
            'id_jeu' => $firstGame->id_jeu,
        ]);

        $response = $this->get('/api/v0/games', ['query' => ['page' => 1]]);

        $response->assertOk();

        $response->assertJsonFragment([
            'data' => [
                0 => [
                    'id' => 1,
                    'status' => 'applied',
                    'title' => 'Fake Game',
                    'session' => [
                        'id' => 1,
                        'name' => 'Session 2001',
                    ],
                    'genre' => 'Adventure game',
                    'software' => 'RPG Maker 2003',
                    'theme' => 'Faking',
                    'duration' => '2:00',
                    'size' => 130,
                    'website' => 'https://fake-game.com',
                    'creation_group' => 'Faking Games Software',
                    'logo' => 'http://fake-alex-dor.test/uploads/logos/fake-game-logo.png',
                    'created_at' => '2020-04-01T12:00:00+0200',
                    'description' => 'Just a sample game in order to test',
                ]
            ]
        ]);
    }
}
