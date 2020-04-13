<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Game;
use Faker\Generator as Faker;

$factory->define(App\Former\Screenshot::class, function (Faker $faker) {
    return [
        'id_jeu' => factory(Game::class),
        'nom_screenshot' => $faker->sentence,
        'local' => 'screenshot.jpg',
        'ordre' => $faker->numberBetween(1, 5),
    ];
});
