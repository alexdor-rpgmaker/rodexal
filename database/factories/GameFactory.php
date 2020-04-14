<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Session;
use Faker\Generator as Faker;

$factory->define(App\Former\Game::class, function (Faker $faker) {
    return [
        'nom_jeu' => $faker->words(3, true),
        'id_session' => factory(Session::class),
        'statut_jeu' => 1,
        'date_inscription' => now()
    ];
});

$factory->state(App\Former\Game::class, 'deleted', [
    'statut_jeu' => 0
]);