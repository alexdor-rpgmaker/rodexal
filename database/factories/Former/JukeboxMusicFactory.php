<?php

namespace Database\Factories\Former;

use App\Former\Game;
use App\Former\JukeboxMusic;
use App\Former\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class JukeboxMusicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JukeboxMusic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_posteur' => Member::inRandomOrder()->first()->id_membre,
            'id_jeu_origine' => Game::inRandomOrder()->first()->id_jeu,
            'titre' => $this->faker->sentence,
            'com' => $this->faker->unique()->paragraphs(mt_rand(1, 3), true),
            'statut_zik' => 1,
            'url_fichier' => 'game_zik',
            'date_publication' => now(),
        ];
    }
}
