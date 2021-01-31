<?php

namespace Database\Factories;

use App\Former\Game;
use App\Former\Contributor;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContributorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contributor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_jeu' => Game::factory(),
            'nom_membre' => $this->faker->userName,
            'mail_membre' => $this->faker->email,
            'statut_participant' => 1,
        ];
    }
}
