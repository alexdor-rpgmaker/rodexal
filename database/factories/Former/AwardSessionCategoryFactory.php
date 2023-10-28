<?php

namespace Database\Factories\Former;

use App\Former\Session;
use App\Former\AwardGeneralCategory;
use App\Former\AwardSessionCategory;

use Illuminate\Database\Eloquent\Factories\Factory;

class AwardSessionCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AwardSessionCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id_serie_categorie' => AwardGeneralCategory::factory(),
            'nom_categorie' => $this->faker->words(3, true),
            'description_categorie' => $this->faker->paragraph(2),
            'niveau_categorie' => $this->faker->numberBetween(1, 4),
            'id_session' => Session::factory(),
            'date_ajout_categorie' => now(),
            'is_declinaison' => $this->faker->boolean,
            'is_after_tests' => $this->faker->boolean,
            'ordre' => $this->faker->numberBetween(1, 20),
        ];
    }
}
