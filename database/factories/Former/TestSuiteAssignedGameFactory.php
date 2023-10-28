<?php

namespace Database\Factories\Former;

use App\Former\Game;
use App\Former\TestSuite;
use App\Former\TestSuiteAssignedGame;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestSuiteAssignedGameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TestSuiteAssignedGame::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id_jeu' => Game::factory(),
            'id_serie' => TestSuite::factory(),
        ];
    }
}
