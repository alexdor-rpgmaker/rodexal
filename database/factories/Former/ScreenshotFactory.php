<?php

namespace Database\Factories\Former;

use App\Former\Game;
use App\Former\Screenshot;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScreenshotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Screenshot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id_jeu' => Game::factory(),
            'nom_screenshot' => $this->faker->sentence,
            'local' => 'screenshot.jpg',
            'ordre' => $this->faker->numberBetween(1, 5),
        ];
    }
}
