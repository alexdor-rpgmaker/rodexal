<?php /** @noinspection PhpUndefinedVariableInspection */

use Faker\Generator as Faker;

$factory->define(App\Former\Session::class, function (Faker $faker) {
    return [
        'statut_session' => 1,
        'nom_session' => "Session " . (2000 + $faker->numberBetween(1, 20)),
        'etape' => $faker->numberBetween(1, 5),
    ];
});
