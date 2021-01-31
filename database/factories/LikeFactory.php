<?php

namespace Database\Factories;

use App\Former\Game;
use App\Former\Like;
use App\Former\Member;

use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_jeu' => Game::factory(),
            'id_membre' => Member::factory(),
            'date_modification' => now(),
        ];
    }
}
