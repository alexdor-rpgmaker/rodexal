<?php

namespace Database\Factories\Former;

use App\Former\Game;
use App\Former\Juror;
use App\Former\TestSuite;
use App\Former\TestSuiteAssignedJuror;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestSuiteAssignedJurorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TestSuiteAssignedJuror::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_jeu' => Game::factory(),
            'id_jury' => Juror::factory(),
            'id_serie' => TestSuite::factory(),
        ];
    }
}
