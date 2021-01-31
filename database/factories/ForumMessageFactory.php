<?php

namespace Database\Factories;

use App\Former\Member;
use App\Former\ForumMessage;
use App\Former\ForumSection;

use Illuminate\Database\Eloquent\Factories\Factory;

class ForumMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ForumMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'statut_message' => 1,
            'type_message' => mt_rand(0, 2),
            'id_membre' => Member::factory(),
            'id_forum' => ForumSection::factory(),
            'titre_message' => $this->faker->words(3, true),
            'sous_titre_message' => $this->faker->words(3, true),
            'date_publication' => now(),
        ];
    }
}
