<?php

namespace Database\Factories;

use App\Former\ForumCategory;

use Illuminate\Database\Eloquent\Factories\Factory;

class ForumCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ForumCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom_categorie' => $this->faker->words(3, true),
            'permission' => mt_rand(0, 6),
            'position' => mt_rand(1, 10),
        ];
    }
}
