<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Game;
use App\Former\TestSuite;
use Faker\Generator as Faker;

$factory->define(App\Former\TestSuiteAssignedGame::class, function (Faker $faker) {
    return [
        'id_jeu' => factory(Game::class),
        'id_serie' => factory(TestSuite::class),
    ];
});
