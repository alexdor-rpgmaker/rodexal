<?php

namespace Feature;

use App\Former\Game;
use App\Former\Juror;
use App\Former\Member;
use App\Former\Session;
use App\Former\TestSuite;
use App\Former\TestSuiteAssignedJuror;
use App\User;
use Tests\Feature\FeatureTestCase;

/**
 * @testdox PreQualificationsRouter
 */
class PreQualificationsRouterTest extends FeatureTestCase
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

    // Create

    /**
     * @test
     * @testdox Create - A non-juror cannot access the new pre-qualification form
     * On ne peut pas accéder au formulaire d'ajout de pré-qualification si on n'est pas juré
     */
    public function create_ifNonJuror_thenForbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pre_qualifications/creer');

        $response->assertForbidden()
            ->assertSeeText('Vous devez être un juré pour créer un QCM !');
    }

    /**
     * @test
     * @testdox Create - Creating a pre-qualification is forbidden if game is not assigned to the juror
     * On ne peut pas accéder au formulaire d'ajout de pré-qualification si le jeu ne nous est pas attribué
     */
    public function create_ifGameNotAssigned_thenForbidden()
    {
        $user = User::factory()->jury()->create();

        $response = $this->actingAs($user)
            ->get('/pre_qualifications/creer?game_id=5');

        $response->assertForbidden()
            ->assertSeeText('Ce jeu ne vous est pas attribué !');
    }

    /**
     * @test
     * @testdox Create - If juror and game is assigned, then we can access new pre-qualification form
     * On peut accéder au formulaire d'ajout de pré-qualification si on est juré
     */
    public function create_ifGameIsAssigned_thenOk()
    {
        $member = Member::factory()->create([
            'id_membre' => 456,
        ]);
        $user = User::factory()->jury()->create([
            'id' => $member->id_membre,
        ]);

        $game = Game::factory()->create([
            'id_jeu' => 3,
            'id_session' => $this->currentSession->id_session,
        ]);
        $juror = Juror::factory()->create([
            'id_membre' => $member->id_membre,
            'id_session' => $this->currentSession->id_session,
            'statut_jury' => 1,
        ]);
        $suite = TestSuite::factory()->create([
            'id_serie' => 123,
            'is_pre_test' => 1,
            'nom_serie' => 'Pré-Tests de 2021',
        ]);
        TestSuiteAssignedJuror::factory()->create([
            'id_jeu' => $game->id_jeu,
            'id_jury' => $juror->id_jury,
            'id_serie' => $suite->id_serie,
            'statut_jeu_jure' => 2,
        ]);

        $response = $this->actingAs($user)
            ->get('/pre_qualifications/creer?game_id=3');

        $response->assertOk();
    }
}
