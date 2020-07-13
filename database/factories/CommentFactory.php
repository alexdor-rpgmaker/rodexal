<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Member;
use App\Former\NewsPost;

use Faker\Generator as Faker;

$factory->define(App\Former\Comment::class, function (Faker $faker) {
    return [
        'id_news' => factory(NewsPost::class),
        'id_membre' => factory(Member::class),
        'contenu_commentaire' => $faker->paragraphs(2, true),
        'statut_commentaire' => 1,
        'date_publication' => now(),
    ];
});
