<?php

namespace Tests\Feature\Api\V0;

use App\User;
use App\PreTest;
use App\Former\Game;
use App\Former\Session;

use Carbon\Carbon;
use Tests\Feature\FeatureTest;

/**
 * @testdox PreTestsApiRouter
 */
class PreTestsApiRouterTest extends FeatureTest
{
    /**
     * @test
     * @testdox We can read the JSON list of filled QCM
     * On peut lire la liste en JSON des QCM remplis
     */
    public function index_whenSessionHasPreTests_returnsPreTestsList()
    {
        Session::factory()->create([
            'id_session' => 21,
        ]);
        Game::factory()->create([
            'id_jeu' => 789,
            'id_session' => 21,
        ]);
        User::factory()->create([
            'id' => 456,
        ]);
        PreTest::factory()->create([
            'id' => 123,
            'user_id' => 456,
            'game_id' => 789,
            'final_thought' => 1,
            'final_thought_explanation' => 'Whatever',
            'created_at' => Carbon::create(2021, 3, 10, 18, 9, 17),
            'updated_at' => Carbon::create(2021, 3, 13, 9, 18, 23),
        ]);
        PreTest::factory()->count(2)->create([
            'game_id' => 789,
        ]);

        self::createOtherSessionGameAndPreTest();

        $response = $this->get('/api/v0/qcm?session_id=21');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJsonPath('0.id', 123)
            ->assertJsonPath('0.user_id', 456)
            ->assertJsonPath('0.game_id', 789)
            ->assertJsonPath('0.final_thought', 1)
            ->assertJsonPath('0.created_at', '2021-03-10T17:09:17.000000Z') // TODO : Fix timezone ?
            ->assertJsonPath('0.updated_at', '2021-03-13T08:18:23.000000Z')
            ->assertJsonPath('1.game_id', 789)
            ->assertJsonPath('2.game_id', 789);
    }

    private static function createOtherSessionGameAndPreTest() {
        Session::factory()->create([
            'id_session' => 20,
        ]);
        Game::factory()->create([
            'id_jeu' => 999,
            'id_session' => 20,
        ]);
        PreTest::factory()->count(2)->create([
            'game_id' => 999,
        ]);
    }
}
