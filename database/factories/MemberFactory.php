<?php /** @noinspection PhpUndefinedVariableInspection */

use Faker\Generator as Faker;

$factory->define(App\Former\Member::class, function (Faker $faker) {
    return [
        'pseudo' => $faker->userName,
        'passe' => $faker->password,
        'mail' => $faker->email,
        'statut_membre' => 1,
        'date_inscription' => now(),
    ];
});
