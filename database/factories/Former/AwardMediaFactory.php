<?php

namespace Database\Factories\Former;

use App\Former\Game;
use App\Former\Member;
use App\Former\AwardMedia;
use App\Former\AwardSessionCategory;

use Illuminate\Database\Eloquent\Factories\Factory;

class AwardMediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AwardMedia::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_jeu' => Game::factory(),
            'id_artiste' => Member::factory(),
            'id_categorie' => AwardSessionCategory::factory(),
            'date_ajout_media' => now(),
            'is_placeholder' => false,
        ];
    }
}
