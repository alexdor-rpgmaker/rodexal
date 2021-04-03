<?php

namespace Database\Factories\Former;

use App\Former\Member;
use App\Former\Comment;
use App\Former\NewsPost;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_parent' => NewsPost::factory(),
            'id_membre' => Member::factory(),
            'contenu_commentaire' => $this->faker->paragraphs(2, true),
            'statut_commentaire' => 1,
            'date_publication' => now(),
        ];
    }
}
