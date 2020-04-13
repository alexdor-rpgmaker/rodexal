<?php

namespace Tests\Feature\Api\V0;

use App\Former\Game;
use App\Former\Member;
use App\Former\Screenshot;
use App\Former\Session;
use App\Former\Contributor;

use Tests\Feature\FeatureTest;

/**
 * @testdox GameApiRouter
 */
class GameApiRouterTest extends FeatureTest
{
    /**
     * @testdox On peut accéder à la liste des jeux via l'API
     */
    public function testGamesApiIndex()
    {
        $session = factory(Session::class)->create([
            'id_session' => 1,
            'etape' => 3,
            'nom_session' => 'Session 2001',
        ]);
        $firstGame = factory(Game::class)->create([
            'id_jeu' => 1,
            'id_session' => $session->id_session,
            'statut_jeu' => 2,
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
        factory(Screenshot::class)->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_screenshot' => 'Second screenshot',
            'local' => 'screenshot-2.jpg',
            'ordre' => 2
        ]);
        factory(Screenshot::class)->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_screenshot' => 'First screenshot',
            'local' => 'screenshot-1.jpg',
            'ordre' => 1
        ]);
        factory(Screenshot::class)->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_screenshot' => 'Deleted screenshot',
            'local' => 'screenshot-x.jpg',
            'ordre' => 3,
            'statut_screenshot' => 0
        ]);

        factory(Game::class)->create([
            'id_jeu' => 2,
            'id_session' => $session->id_session,
            'statut_jeu' => 1,
            'nom_jeu' => 'Fake Game 2',
            'genre_jeu' => 'Racing game',
            'support' => 'RPG Maker XP',
            'theme' => 'Faking',
            'duree' => '1:00',
            'poids' => '110',
            'site_officiel' => 'https://fake-game2.com',
            'description_jeu' => 'Just a second sample game in order to test',
            'groupe' => null,
            'logo' => null,
            'logo_distant' => 'https://fake-game2.com/logo.png',
            'date_inscription' => '2020-04-03 18:00:00',
        ]);
        factory(Game::class)->states('deleted')->create();

        $response = $this->get('/api/v0/games', ['query' => ['page' => 1]]);

        $response->assertOk();

        // Data count
        $response->assertJsonCount(2, 'data');

        // Pagination
        $response->assertJsonFragment([
            "current_page" => 1,
            "from" => 1,
            "last_page" => 1,
            "per_page" => 30,
            "to" => 2,
            "total" => 2,

            "path" => "http://rodexal.test/api/v0/games",
            "first_page_url" => "http://rodexal.test/api/v0/games?page=1",
            "last_page_url" => "http://rodexal.test/api/v0/games?page=1",
            "prev_page_url" => null,
            "next_page_url" => null,
        ]);

        // Data
        $response->assertJsonFragment([
            'data' => [
                [
                    'id' => 1,
                    'status' => 'qualified',
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
                    'logo' => 'http://alex-dor.test/uploads/logos/fake-game-logo.png',
                    'created_at' => '2020-04-01T12:00:00+02:00',
                    'description' => 'Just a sample game in order to test',
                    'download_links' => [
                        [
                            'platform' => 'windows',
                            'url' => 'http://alex-dor.test/archives/2001/jeux/fake_game.zip'
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
                    'screenshots' => [
                        [
                            'title' => 'First screenshot',
                            'url' => 'http://alex-dor.test/uploads/screenshots/2001/screenshot-1.jpg'
                        ],
                        [
                            'title' => 'Second screenshot',
                            'url' => 'http://alex-dor.test/uploads/screenshots/2001/screenshot-2.jpg'
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'status' => 'not_qualified',
                    'title' => 'Fake Game 2',
                    'session' => [
                        'id' => 1,
                        'name' => 'Session 2001',
                    ],
                    'genre' => 'Racing game',
                    'software' => 'RPG Maker XP',
                    'theme' => 'Faking',
                    'duration' => '1:00',
                    'size' => 110,
                    'website' => 'https://fake-game2.com',
                    'creation_group' => null,
                    'logo' => 'https://fake-game2.com/logo.png',
                    'created_at' => '2020-04-03T18:00:00+02:00',
                    'description' => 'Just a second sample game in order to test',
                    'download_links' => [],
                    'authors' => [],
                    'screenshots' => []
                ]
            ]
        ]);
    }
}
