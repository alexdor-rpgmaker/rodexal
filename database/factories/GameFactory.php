<?php /** @noinspection PhpUndefinedVariableInspection */

use Faker\Generator as Faker;

$factory->define(App\Former\Game::class, function (Faker $faker) {
    return [
        'nom_jeu' => $faker->words,
        'statut_jeu' => 1,
    ];
});
