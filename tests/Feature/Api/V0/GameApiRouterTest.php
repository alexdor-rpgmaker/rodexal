<?php

namespace Tests\Feature\Api\V0;

use App\Former\Game;
use App\Former\Member;
use App\Former\Session;
use App\Former\Screenshot;
use App\Former\Nomination;
use App\Former\Contributor;
use App\Former\AwardSessionCategory;

use Tests\Feature\FeatureTest;

/**
 * @testdox GameApiRouter
 */
class GameApiRouterTest extends FeatureTest
{
    // TODO : Generate data in refreshDatabase instead of in each test
    // like in GamesRouterTest ; in order to increase speed.

    public function setUp(): void
    {
        parent::refreshDatabaseOnNextSetup();
        parent::setUp();
    }

    /**
     * @test
     * @testdox We can list games from API
     * On peut accéder à la liste des jeux via l'API
     */
    public function listGamesWithPagination()
    {
        $session = Session::factory()->create([
            'id_session' => 1,
            'etape' => 3,
            'nom_session' => 'Session 2001',
        ]);
        $firstGame = Game::factory()->create([
            'id_jeu' => 1,
            'id_session' => $session->id_session,
            'statut_jeu' => 'qualified',
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
            'lien_sur_site' => 'fake_game.zip',
        ]);
        $membre = Member::factory()->create([
            'id_membre' => 3,
            'pseudo' => 'Juan-Pablo',
            'rang' => 4,
        ]);
        Contributor::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'id_membre' => $membre->id_membre,
            'nom_membre' => null,
            'role' => 'Programmer',
            'ordre' => 2,
        ]);
        Contributor::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_membre' => 'Anita',
            'role' => 'Game designer',
            'ordre' => 1,
        ]);
        Contributor::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_membre' => 'León',
            'role' => 'Debugger',
            'ordre' => 3,
            'statut_participant' => 0,
        ]);
        Screenshot::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_screenshot' => 'Second screenshot',
            'local' => 'screenshot-2.jpg',
            'ordre' => 2,
        ]);
        Screenshot::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_screenshot' => 'First screenshot',
            'local' => 'screenshot-1.jpg',
            'ordre' => 1,
        ]);
        Screenshot::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'nom_screenshot' => 'Deleted screenshot',
            'local' => 'screenshot-x.jpg',
            'ordre' => 3,
            'statut_screenshot' => 0,
        ]);

        Nomination::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'is_vainqueur' => 1,
            'id_categorie' => AwardSessionCategory::factory()->create([
                'nom_categorie' => 'Gameplay',
                'is_declinaison' => true,
            ]),
        ]);
        Nomination::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'is_vainqueur' => 0,
            'id_categorie' => AwardSessionCategory::factory()->create([
                'nom_categorie' => 'Atmosphere',
                'is_declinaison' => true,
            ]),
        ]);
        Nomination::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'is_vainqueur' => 1,
            'id_categorie' => AwardSessionCategory::factory()->create([
                'nom_categorie' => 'Deleted category',
                'is_declinaison' => false,
                'statut_categorie' => 0,
            ]),
        ]);

        $secondGame = Game::factory()->create([
            'id_jeu' => 2,
            'id_session' => $session->id_session,
            'statut_jeu' => 'registered',
            'nom_jeu' => 'Fake Game 2',
            'genre_jeu' => 'Racing game',
            'support' => 'RPG Maker XP',
            'theme' => '', // Empty string for theme
            'duree' => '1:00',
            'poids' => '110',
            'site_officiel' => 'https://fake-game2.com',
            'description_jeu' => 'Just a second sample game in order to test',
            'groupe' => null,
            'logo' => null,
            'logo_distant' => 'https://fake-game2.com/logo.png',
            'date_inscription' => null, // Null date
        ]);

        Contributor::factory()->create([
            'id_jeu' => $secondGame,
            'nom_membre' => 'Paul',
            'role' => '',
            'ordre' => 3,
            'statut_participant' => 0,
        ]);

        Game::factory()->deleted()->create();

        $response = $this->get('/api/v0/games', ['query' => ['page' => 1]]);

        $response->assertOk();

        // Data count
        $response->assertJsonCount(2, 'data');

        // Pagination
        $response->assertJsonFragment([
            "meta" => [
                "current_page" => 1,
                "from" => 1,
                "last_page" => 1,
                "per_page" => 50,
                "to" => 2,
                "total" => 2,
                "path" => "https://rodexal.test/api/v0/games",
                "links" => [
                    [
                        "url" => null,
                        "active" => false,
                        "label" => "&laquo; Précédent",
                    ],
                    [
                        "url" => null,
                        "active" => false,
                        "label" => "Suivant &raquo;",
                    ],
                    [
                        "url" => "https://rodexal.test/api/v0/games?page=1",
                        "active" => true,
                        "label" => "1",
                    ]
                ]
            ],
            "links" => [
                "first" => "https://rodexal.test/api/v0/games?page=1",
                "last" => "https://rodexal.test/api/v0/games?page=1",
                "prev" => null,
                "next" => null,
            ],
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
                    'logo' => 'https://alex-dor.test/uploads/logos/fake-game-logo.png',
                    'created_at' => '2020-04-01T12:00:00+02:00',
                    'description' => 'Just a sample game in order to test',
                    'download_links' => [
                        [
                            'platform' => 'windows',
                            'url' => 'https://alex-dor.test/archives/2001/jeux/fake_game.zip',
                        ],
                    ],
                    'authors' => [
                        [
                            'id' => null,
                            'rank' => null,
                            'username' => 'Anita',
                            'role' => 'Game designer',
                        ],
                        [
                            'id' => 3,
                            'rank' => 'juror',
                            'username' => 'Juan-Pablo',
                            'role' => 'Programmer',
                        ],
                    ],
                    'screenshots' => [
                        [
                            'title' => 'First screenshot',
                            'url' => 'https://alex-dor.test/uploads/screenshots/2001/screenshot-1.jpg',
                        ],
                        [
                            'title' => 'Second screenshot',
                            'url' => 'https://alex-dor.test/uploads/screenshots/2001/screenshot-2.jpg',
                        ],
                    ],
                    'awards' => [
                        [
                            'status' => 'awarded',
                            'award_level' => 'gold',
                            'category_name' => 'Gameplay',
                        ],
                        [
                            'status' => 'nominated',
                            'award_level' => null,
                            'category_name' => 'Atmosphere',
                        ],
                    ],
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
                    'theme' => null,
                    'duration' => '1:00',
                    'size' => 110,
                    'website' => 'https://fake-game2.com',
                    'creation_group' => null,
                    'logo' => 'https://fake-game2.com/logo.png',
                    'created_at' => null,
                    'description' => 'Just a second sample game in order to test',
                    'download_links' => [],
                    'authors' => [],
                    'screenshots' => [],
                    'awards' => []
                ]
            ]
        ]);

        // Sort authors and screenshots
        $response->assertJsonPath('data.0.authors.0.username', 'Anita')
            ->assertJsonPath('data.0.authors.1.username', 'Juan-Pablo')
            ->assertJsonPath('data.0.screenshots.0.title', 'First screenshot')
            ->assertJsonPath('data.0.screenshots.1.title', 'Second screenshot');
    }

    /**
     * @test
     * @testdox We can filter games list from API
     * On peut filtrer la liste de jeux de l'API
     */
    public function listGamesWithFilters()
    {
        $session = Session::factory()->create([
            'id_session' => 1,
            'nom_session' => 'Session 2001',
        ]);

        // 5 relevant games

        Game::factory()->create([
            'nom_jeu' => 'Game with search query in genre',
            'genre_jeu' => '--My amazing search query--',
            'support' => 'RPG Maker 2003',
            'theme' => 'Fake theme',
            'description_jeu' => 'Just a sample game in order to test',
            'groupe' => 'Faking Games Software',
            'id_session' => $session
        ]);

        Game::factory()->create([
            'nom_jeu' => '--My amazing search query--',
            'genre_jeu' => 'Adventure game',
            'support' => 'RPG Maker 2003',
            'theme' => 'Fake theme',
            'description_jeu' => 'Game with search query in name',
            'groupe' => 'Faking Games Software',
            'id_session' => $session
        ]);

        Game::factory()->create([
            'nom_jeu' => 'Game with search query in description',
            'genre_jeu' => 'Adventure game',
            'support' => 'RPG Maker 2003',
            'theme' => 'Fake theme',
            'description_jeu' => '--My amazing search query--',
            'groupe' => 'Faking Games Software',
            'id_session' => $session
        ]);

        Game::factory()->create([
            'nom_jeu' => 'Game with search query in theme',
            'genre_jeu' => 'Adventure game',
            'support' => 'RPG Maker 2003',
            'theme' => '--My amazing search query--',
            'description_jeu' => 'Just a sample game in order to test',
            'groupe' => 'Faking Games Software',
            'id_session' => $session
        ]);

        Game::factory()->create([
            'nom_jeu' => 'Game with search query in group',
            'genre_jeu' => 'Adventure game',
            'support' => 'RPG Maker 2003',
            'theme' => 'Fake theme',
            'description_jeu' => 'Just a sample game in order to test',
            'groupe' => '--My amazing search query--',
            'id_session' => $session
        ]);

        // Irrelevant games

        Game::factory()->create([
            'nom_jeu' => 'Game with wrong session',
            'genre_jeu' => 'My amazing search query',
            'support' => 'RPG Maker 2003',
            'theme' => 'Fake theme',
            'description_jeu' => 'Just a sample game in order to test',
            'groupe' => 'Faking Games Software',
            'id_session' => Session::factory()->create([
                'id_session' => 2,
                'nom_session' => 'Session 2002',
            ])
        ]);

        Game::factory()->create([
            'nom_jeu' => 'Game with wrong support/software',
            'genre_jeu' => 'My amazing search query',
            'support' => 'RPG Maker XP',
            'theme' => 'Fake theme',
            'description_jeu' => 'Just a sample game in order to test',
            'groupe' => 'Faking Games Software',
            'id_session' => $session
        ]);

        Game::factory()->create([
            'nom_jeu' => 'Game without search query',
            'genre_jeu' => 'Adventure game',
            'support' => 'RPG Maker 2003',
            'theme' => 'Fake theme',
            'description_jeu' => 'Just a sample game in order to test',
            'groupe' => 'Faking Games Software',
            'id_session' => $session
        ]);

        $queryParameters = [
            'q' => 'My amazing search query',
            'session_id' => 1,
            'software' => 'RPG Maker 2003'
        ];
        $response = $this->call('GET', '/api/v0/games', $queryParameters);

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('data.0.title', 'Game with search query in genre')
            ->assertJsonPath('data.1.description', 'Game with search query in name')
            ->assertJsonPath('data.2.title', 'Game with search query in description')
            ->assertJsonPath('data.3.title', 'Game with search query in theme')
            ->assertJsonPath('data.4.title', 'Game with search query in group');
    }

    /**
     * @test
     * @testdox We can filter games list depending on download links from API
     * On peut filtrer la liste de jeux de l'API par lien de téléchargement
     */
    public function listGamesFilteredByDownloadLink()
    {
        Game::factory()->create([
            'id_jeu' => 1,
            'nom_jeu' => 'Game without download links',
        ]);

        Game::factory()->create([
            'id_jeu' => 2,
            'nom_jeu' => 'Game with Windows link',
            'lien' => 'https://windows-link',
        ]);

        Game::factory()->create([
            'id_jeu' => 3,
            'nom_jeu' => 'Game with Mac link',
            'lien_sur_mac' => 'https://mac-link',
        ]);

        Game::factory()->create([
            'id_jeu' => 4,
            'nom_jeu' => 'Game with Windows link',
            'lien_sur_site' => 'https://windows-link',
        ]);

        Game::factory()->create([
            'id_jeu' => 5,
            'nom_jeu' => 'Game with Mac link',
            'lien_sur_site_sur_mac' => 'https://mac-link',
        ]);

        Game::factory()->create([
            'id_jeu' => 6,
            'nom_jeu' => 'Game with both platform links',
            'lien' => 'https://windows-link',
            'lien_sur_mac' => 'https://mac-link',
        ]);

        Game::factory()->create([
            'id_jeu' => 7,
            'nom_jeu' => 'Game with link removed by author',
            'lien' => 'https://windows-link',
            'lien_sur_mac' => 'https://mac-link',
            'link_removed_on_author_demand' => true,
        ]);

        Game::factory()->create([
            'id_jeu' => 8,
            'nom_jeu' => 'Game with unavailable link',
            'lien' => 'https://windows-link',
            'lien_sur_mac' => 'https://mac-link',
            'is_lien_errone' => true,
        ]);

        // Windows games

        $queryParameters = ['download_links' => 'windows'];
        $response = $this->call('GET', '/api/v0/games', $queryParameters);

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', 2)
            ->assertJsonPath('data.1.id', 4)
            ->assertJsonPath('data.2.id', 6);

        // Mac games

        $queryParameters = ['download_links' => 'mac'];
        $response = $this->call('GET', '/api/v0/games', $queryParameters);

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', 3)
            ->assertJsonPath('data.1.id', 5)
            ->assertJsonPath('data.2.id', 6);

        // Games with links

        $queryParameters = ['download_links' => 'any'];
        $response = $this->call('GET', '/api/v0/games', $queryParameters);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        // Games without links

        $queryParameters = ['download_links' => 'none'];
        $response = $this->call('GET', '/api/v0/games', $queryParameters);

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', 1)
            ->assertJsonPath('data.1.id', 7)
            ->assertJsonPath('data.2.id', 8);
    }

    /**
     * @test
     * @testdox We can sort games list from API
     * On peut ordonner la liste de jeux de l'API
     */
    public function listGamesWithSorting()
    {
        Game::factory()->create([
            'id_jeu' => 1,
            'nom_jeu' => 'Game in old session (2001)',
            'support' => 'RPG Maker XP',
            'id_session' => Session::factory()->create([
                'id_session' => 1,
                'nom_session' => 'Session 2001',
            ])
        ]);

        $session = Session::factory()->create([
            'id_session' => 20,
            'nom_session' => 'Session 2020',
        ]);

        Game::factory()->create([
            'id_jeu' => 2,
            'nom_jeu' => 'AAAA',
            'support' => 'ZZZZ',
            'id_session' => $session
        ]);

        Game::factory()->create([
            'id_jeu' => 3,
            'nom_jeu' => 'BBBB',
            'support' => 'ZZZZ',
            'id_session' => $session
        ]);

        Game::factory()->create([
            'id_jeu' => 4,
            'nom_jeu' => 'CCCC',
            'support' => 'YYYY',
            'id_session' => $session
        ]);

        // With requested sorting

        $queryParameters = [
            'sort' => 'session:desc,software:asc,title'
        ];
        $response = $this->call('GET', '/api/v0/games', $queryParameters);

        $response->assertOk()
            ->assertJsonPath('data.0.id', 4)
            ->assertJsonPath('data.1.id', 2)
            ->assertJsonPath('data.2.id', 3)
            ->assertJsonPath('data.3.id', 1);

        // With default sorting

        $responseWithDefaultSorting = $this->call('GET', '/api/v0/games');

        $responseWithDefaultSorting->assertOk()
            ->assertJsonPath('data.0.id', 1)
            ->assertJsonPath('data.1.id', 2)
            ->assertJsonPath('data.2.id', 3)
            ->assertJsonPath('data.3.id', 4);
    }

    /**
     * @test
     * @testdox We can sort games list by awards count
     * On peut ordonner la liste de jeux de l'API par nombre d'awards
     */
    public function listGamesWithSortingOnAwardsCount()
    {
        $firstGame = Game::factory()->create([
            'id_jeu' => 1,
        ]);

        $secondGame = Game::factory()->create([
            'id_jeu' => 2,
        ]);

        $thirdGame = Game::factory()->create([
            'id_jeu' => 3,
        ]);

        Game::factory()->create([
            'id_jeu' => 4,
        ]);

        Nomination::factory()->create([
            'id_jeu' => $thirdGame->id_jeu,
            'is_vainqueur' => 1,
            'id_categorie' => AwardSessionCategory::factory()->create(),
        ]);

        Nomination::factory()->create([
            'id_jeu' => $thirdGame->id_jeu,
            'is_vainqueur' => 1,
            'id_categorie' => AwardSessionCategory::factory()->create(),
        ]);

        Nomination::factory()->create([
            'id_jeu' => $secondGame->id_jeu,
            'is_vainqueur' => 1,
            'id_categorie' => AwardSessionCategory::factory()->create(),
        ]);

        Nomination::factory()->create([
            'id_jeu' => $secondGame->id_jeu,
            'is_vainqueur' => 0,
            'id_categorie' => AwardSessionCategory::factory()->create(),
        ]);

        Nomination::factory()->create([
            'id_jeu' => $firstGame->id_jeu,
            'is_vainqueur' => 0,
            'id_categorie' => AwardSessionCategory::factory()->create(),
        ]);

        $queryParameters = [
            'sort' => 'awards_count:desc'
        ];
        $response = $this->call('GET', '/api/v0/games', $queryParameters);

        $response->assertOk()
            ->assertJsonPath('data.0.id', 3)
            ->assertJsonPath('data.1.id', 2)
            ->assertJsonPath('data.2.id', 1)
            ->assertJsonPath('data.3.id', 4);
    }
}
