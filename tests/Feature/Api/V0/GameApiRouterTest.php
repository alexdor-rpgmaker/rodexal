<?php

namespace Tests\Feature;

use App\Former\Game;
use App\Former\Member;
use App\Former\Session;
use App\Former\Contributor;

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
            'etape' => 1,
            'nom_session' => 'Session 2001',
        ]);
        $firstGame = factory(Game::class)->create([
            'id_session' => $session->id_session,
            'statut_jeu' => 1,
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
            'lien' => 'https://www.fake-downloads.com/8764789.zip',
            'lien_sur_site' => 'fake_game.zip'
        ]);
        $membre = factory(Member::class)->create([
            'id_membre' => 3,
            'pseudo' => 'Juan-Pablo'
        ]);
        factory(Contributor::class)->create([
            'id_jeu' => $firstGame->id_jeu,
            'id_membre' => $membre->id_membre,
            'nom_membre' => null,
            'role' => 'Programmer',
            'ordre' => 2
        ]);
        factory(Contributor::class)->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_membre' => 'Anita',
            'role' => 'Game designer',
            'ordre' => 1
        ]);
        factory(Contributor::class)->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_membre' => 'León',
            'role' => 'Debugger',
            'ordre' => 3,
            'statut_participant' => 0
        ]);

        $response = $this->get('/api/v0/games', ['query' => ['page' => 1]]);

        $response->assertOk();

        $response->assertJsonFragment([
            'data' => [
                [
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
                    'download_links' => [
                        [
                            'platform' => 'windows',
                            'url' => 'http://fake-alex-dor.test/archives/2001/jeux/fake_game.zip'
                        ]
                    ],
                    'authors' => [
                        [
                            'id' => null,
                            'username' => 'Anita',
                            'role' => 'Game designer',
                        ],
                        [
                            'id' => 3,
                            'username' => 'Juan-Pablo',
                            'role' => 'Programmer',
                        ]
                    ],
                ]
            ]
        ]);
    }
}
