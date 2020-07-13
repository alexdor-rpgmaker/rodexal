<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\ForumCategory;
use Faker\Generator as Faker;

$factory->define(App\Former\ForumSection::class, function (Faker $faker) {
    return [
        'id_categorie' => factory(ForumCategory::class),
        'titre_forum' => $faker->words(3, true),
        'sous_titre_forum' => $faker->words(3, true),
        'statut_forum' => 1,
        'permission_forum' => mt_rand(0, 6),
        'position_forum' => mt_rand(1, 10)
    ];
});
