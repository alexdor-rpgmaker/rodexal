<?php

namespace Tests\Feature;

use App\User;
use App\Former\Game;
use App\Former\Member;
use App\Former\Session;

use App\Notifications\GameRegistered;

use Illuminate\Support\Facades\Notification;

/**
 * @testdox GamesRouter
 */
class GamesRouterTest extends FeatureTestCase
{
    private User $currentUser;
    private Session $currentSession;
    private int $initialGamesCount;

    protected function refreshDatabase(): void
    {
        parent::refreshDatabase();

        $user = User::factory()->create([
            'id' => 123
        ]);
        Member::factory()->create([
            'id_membre' => $user->id,
            'pseudo' => 'Random Member',
            'mail' => 'random_member@mail.com'
        ]);
        Session::factory()->create([
            'id_session' => 20,
            'nom_session' => 'Session 2020',
        ]);
        $currentSession = Session::factory()->create([
            'id_session' => 21,
            'nom_session' => 'Session 2021',
            'etape' => 1,
        ]);
        Game::factory()->create([
            'id_jeu' => 1,
            'support' => 'Software1',
            'id_session' => $currentSession,
        ]);
        Game::factory()->create([
            'id_jeu' => 2,
            'support' => 'Software2',
            'id_session' => $currentSession,
        ]);
    }

    public static function setUpBeforeClass(): void
    {
        parent::refreshDatabaseOnNextSetup();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->initialGamesCount = Game::count();
        $this->currentUser = User::find(123);
        $this->currentSession = Session::find(21);
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

        $expectedSessions = Session::whereIn('id_session', [20, 21])->get();
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
            'session_id' => '20',
        ];
        $response = $this->call('GET', '/jeux', $queryParameters);

        $response->assertOk();

        $response->assertViewHas('softwares', collect(['Software1', 'Software2']));

        $actualGamesCount = count($response['games']);
        $this->assertEquals(0, $actualGamesCount);

        $expectedSelectedSession = Session::find(20);
        $response->assertViewHas('selectedSession', $expectedSelectedSession);

        $expectedSessions = Session::whereIn('id_session', [20, 21])->get();
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
        $session = Session::factory()->create([
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

    // Create

    /**
     * @test
     * @testdox Create - When user is not connected the form is displayed
     * Quand l'utilisateur n'est pas connecté, le formulaire est affiché quand même
     */
    public function store_ifNotConnected_thenOk()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/jeux');

        $response->assertOk();
    }

    /**
     * @test
     * @testdox Create - When we want to add a game, the form is displayed
     * On peut accéder au formulaire d'inscription
     */
    public function create_whenWeWantToAddGame_theFormIsDisplayed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/jeux/inscrire');

        $response->assertOk();
    }

    // Store

    /**
     * @test
     * @testdox Store - If user is not connected, redirects to login
     * Si on n'est pas connecté, redirige vers la page de login
     */
    public function store_ifNotConnected_thenRedirectToLogin()
    {
        $response = $this->post('/jeux');

        $response->assertRedirect('oauth/callback');
    }

    /**
     * @test
     * @testdox Store - If parameters are missing, the form is displayed with errors
     * S'il manque des paramètres pour inscrire un jeu, le formulaire s'affiche à nouveau, avec des erreurs
     */
    public function store_ifAParameterIsMissing_thenDisplaysFormWithErrors()
    {
        $response = $this->actingAs($this->currentUser)
            ->post('/jeux', [
                // No 'title'
                'description' => '', // Empty description
                // No 'progression'
                // No 'software'
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['title', 'description', 'progression', 'software']);
        $this->assertDatabaseCount('jeux', $this->initialGamesCount, 'former_app_database');
        $this->assertDatabaseCount('participants', 0, 'former_app_database');
    }

    /**
     * @test
     * @testdox Store - If current session is currently not open to game registrations, then there is an error
     * Si la session courante n'est pas ouverte à l'inscription des jeux, alors il y a une erreur
     */
    public function store_ifCurrentSessionGamesAreNotRegistrable_thenReturnsAnError()
    {
        $stepWhereGamesCannotBeRegistered = 0;
        $this->currentSession->update(['etape' => $stepWhereGamesCannotBeRegistered]);

        $user = User::factory()->create();
        $response = $this->actingAs($this->currentUser)
            ->post('/jeux', [
                'title' => 'My new game',
                'description' => 'This new game I made is awesome!!',
                'progression' => 'full',
                'software' => 'RPG Maker 2003'
            ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('jeux', $this->initialGamesCount, 'former_app_database');
        $this->assertDatabaseCount('participants', 0, 'former_app_database');

        // Finally
        $this->currentSession->update(['etape' => 1]);
    }

    /**
     * @test
     * @testdox Store - If all parameters are OK, we can register a new game
     * Quand tous les paramètres sont bon, on peut inscrire un jeu
     */
    public function store_ifParametersAreOk_thenGameIsCreated()
    {
        Notification::fake();

        $response = $this->actingAs($this->currentUser)
            ->post('/jeux', [
                'title' => 'My new game',
                'description' => 'This new game I made is awesome!!',
                'progression' => 'full',
                'software' => 'RPG Maker 2003'
            ]);

        $response->assertRedirect('/jeux');

        $lastGame = Game::orderByDesc('id_jeu')->first();
        Notification::assertSentTo(
            $lastGame, GameRegistered::class
        );

        $this->assertDatabaseHas('jeux', [
            'id_jeu' => $lastGame->id_jeu,
            'nom_jeu' => 'My new game',
            'description_jeu' => 'This new game I made is awesome!!',
            'support' => 'RPG Maker 2003',
            'avancement_jeu' => 1,
        ], 'former_app_database');

        $this->assertDatabaseHas('participants', [
            'id_jeu' => $lastGame->id_jeu,
            'id_membre' => 123,
            'nom_membre' => 'Random Member',
            'mail_membre' => 'random_member@mail.com',
            'ordre' => 1,
            'peut_editer_jeu' => 1,
            'statut_participant' => 1
        ], 'former_app_database');
    }
}
