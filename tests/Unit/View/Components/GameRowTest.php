<?php
namespace Tests\Unit\View\Components;

use Mockery;
use Tests\TestCase;

use App\Former\Game;
use App\Former\Contributor;
use App\Former\Nomination;
use App\Former\AwardSessionCategory;
use App\View\Components\GameRow;

/**
 * @testdox GameRow
 */
class GameRowTest extends TestCase
{
    protected $game;
    protected GameRow $gameRow;

    protected function setUp(): void
    {
        parent::setUp();
        $this->game = factory(Game::class)->create();
        $this->gameRow = new GameRow($this->game);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @test
     * @testdox contributors - Lists links or name of contributors
     * Liste le lien ou le nom des participants
     */
    public function contributors()
    {
        $contributor1 = $this->mock(
            Contributor::class,
            fn($mock) => $mock->shouldReceive('getAttribute')
                ->with('linkOrName')
                ->andReturn('<a href="jp">Juan-Pablo</a>')
        );
        $contributor2 = $this->mock(
            Contributor::class,
            fn($mock) => $mock->shouldReceive('getAttribute')
                ->with('linkOrName')
                ->andReturn('Juanita')
        );
        $mockGame = $this->mock(
            Game::class,
            fn($mock) => $mock->shouldReceive('getAttribute')
                ->with('contributors')
                ->andReturn(collect([$contributor1, $contributor2]))
        );

        $gameRow = new GameRow($mockGame);

        $this->assertEquals("<a href=\"jp\">Juan-Pablo</a>, Juanita", $gameRow->contributors());
    }

    /**
     * @test
     * @testdox awardedCategoriesList - Returns the list of awards the game has won
     * Retourne la liste des awards que le jeu a gagnés
     */
    public function awardedCategoriesList()
    {
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 0,
            'id_categorie' => factory(AwardSessionCategory::class),
        ]);
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 1,
            'id_categorie' => factory(AwardSessionCategory::class)->create([
                'nom_categorie' => 'Gameplay',
                'niveau_categorie' => 1,
                'is_declinaison' => true
            ]),
        ]);
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 1,
            'id_categorie' => factory(AwardSessionCategory::class)->create([
                'nom_categorie' => 'Level design',
                'niveau_categorie' => 2,
                'is_declinaison' => false
            ]),
        ]);

        $this->assertEquals("Gameplay (or), Level design", $this->gameRow->awardedCategoriesList());
    }

    /**
     * @test
     * @testdox nominatedCategoriesList - Returns the list of awards for which the game was nominated
     * Retourne la liste des awards pour lesquels le jeu a été nommé
     */
    public function nominatedCategoriesList()
    {
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 0,
            'id_categorie' => factory(AwardSessionCategory::class)->create([
                'nom_categorie' => 'Gameplay',
                'niveau_categorie' => 1,
            ]),
        ]);
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 0,
            'id_categorie' => factory(AwardSessionCategory::class)->create([
                'nom_categorie' => 'Level design',
                'niveau_categorie' => 2,
            ]),
        ]);
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 1,
            'id_categorie' => factory(AwardSessionCategory::class),
        ]);

        $this->assertEquals("Gameplay, Level design", $this->gameRow->nominatedCategoriesList());
    }

    /**
     * @test
     * @testdox wasAwarded - If nomination is winner, returns true
     * Si nomination indique qu'il est vainqueur, retourne vrai
     */
    public function wasAwardedTrue()
    {
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 2,
        ]);

        $this->assertEquals(true, $this->gameRow->wasAwarded());
    }

    /**
     * @test
     * @testdox wasAwarded - If nomination is not winner, returns false
     * Si nomination indique qu'il n'est pas vainqueur, retourne faux
     */
    public function wasAwardedFalse()
    {
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 0,
        ]);

        $this->assertEquals(false, $this->gameRow->wasAwarded());
    }

    /**
     * @test
     * @testdox wasNominated - If nomination is winner, returns false
     * Si nomination indique qu'il est vainqueur, retourne faux
     */
    public function wasNominatedTrue()
    {
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 2,
        ]);

        $this->assertEquals(false, $this->gameRow->wasNominated());
    }

    /**
     * @test
     * @testdox wasNominated - If nomination is not winner, returns true
     * Si nomination indique qu'il n'est pas vainqueur, retourne vrai
     */
    public function wasNominatedFalse()
    {
        factory(Nomination::class)->create([
            'id_jeu' => $this->game->id_jeu,
            'is_vainqueur' => 0,
        ]);

        $this->assertEquals(true, $this->gameRow->wasNominated());
    }
}
