<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Game;
use App\Former\Juror;
use App\Former\Member;
use App\Former\TestSuite;
use Faker\Generator as Faker;

$factory->define(App\Former\Test::class, function (Faker $faker) {
    return [
        'id_jeu' => factory(Game::class),
        'id_jury' => factory(Juror::class),
        'id_serie' => factory(TestSuite::class),
        'statut_test' => 1,
        'date_modification' => now(),
    ];
});

$factory->state(App\Former\Test::class, 'reviewed', function (Faker $faker) {
    return [
        'reviewer_id' => factory(Member::class),
    ];
});
