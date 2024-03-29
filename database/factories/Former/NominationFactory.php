<?php

namespace Database\Factories\Former;

use App\Former\Game;
use App\Former\Nomination;
use App\Former\AwardSessionCategory;

use Illuminate\Database\Eloquent\Factories\Factory;

class NominationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Nomination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id_jeu' => Game::factory(),
            'id_categorie' => AwardSessionCategory::factory(),
            'is_vainqueur' => $this->faker->numberBetween(0, 4),
        ];
    }
}
