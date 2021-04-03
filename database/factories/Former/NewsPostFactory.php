<?php

namespace Database\Factories\Former;

use App\Former\Member;
use App\Former\NewsPost;

use Illuminate\Database\Eloquent\Factories\Factory;

class NewsPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NewsPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom_news' => $this->faker->words(3, true),
            'contenu_news' => $this->faker->paragraphs(2, true),
            'id_membre' => Member::factory(),
            'date_creation_news' => now(),
        ];
    }
}
