<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Game;
use Faker\Generator as Faker;

$factory->define(App\Former\Contributor::class, function (Faker $faker) {
    return [
        'id_jeu' => factory(Game::class),
        'nom_membre' => $faker->userName,
        'mail_membre' => $faker->email,
        'statut_participant' => 1,
    ];
});
