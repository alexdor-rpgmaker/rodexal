<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Game;
use App\Former\Juror;
use App\Former\TestSuite;
use Faker\Generator as Faker;

$factory->define(App\Former\TestSuiteAssignedJuror::class, function (Faker $faker) {
    return [
        'id_jeu' => factory(Game::class),
        'id_jury' => factory(Juror::class),
        'id_serie' => factory(TestSuite::class),
    ];
});
