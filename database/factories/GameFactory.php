<?php

namespace Database\Factories;

use App\Former\Game;
use App\Former\Session;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom_jeu' => $this->faker->words(3, true),
            'id_session' => Session::factory(),
            'statut_jeu' => 1,
            'date_inscription' => now(),
        ];
    }
}
