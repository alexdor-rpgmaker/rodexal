<?php /** @noinspection PhpUndefinedVariableInspection */

use Faker\Generator as Faker;

$factory->define(App\Former\ForumCategory::class, function (Faker $faker) {
    return [
        'nom_categorie' => $faker->words(3, true),
        'permission' => mt_rand(0, 6),
        'position' => mt_rand(1, 10)
    ];
});
