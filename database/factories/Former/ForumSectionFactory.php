<?php

namespace Database\Factories\Former;

use App\Former\ForumSection;
use App\Former\ForumCategory;

use Illuminate\Database\Eloquent\Factories\Factory;

class ForumSectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ForumSection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_categorie' => ForumCategory::factory(),
            'titre_forum' => $this->faker->words(3, true),
            'sous_titre_forum' => $this->faker->words(3, true),
            'statut_forum' => 1,
            'permission_forum' => mt_rand(0, 6),
            'position_forum' => mt_rand(1, 10),
        ];
    }
}
