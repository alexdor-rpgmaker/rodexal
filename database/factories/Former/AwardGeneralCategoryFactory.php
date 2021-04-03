<?php

namespace Database\Factories\Former;

use App\Former\AwardGeneralCategory;

use Illuminate\Database\Eloquent\Factories\Factory;

class AwardGeneralCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AwardGeneralCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom_serie' => $this->faker->words(3, true),
            'description_serie' => $this->faker->paragraph(2),
        ];
    }
}
