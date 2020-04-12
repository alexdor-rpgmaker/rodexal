<?php /** @noinspection PhpUndefinedVariableInspection */

use Faker\Generator as Faker;

$factory->define(App\Former\Participant::class, function (Faker $faker) {
    return [
        'nom_membre' => $faker->userName,
        'mail_membre' => $faker->email,
        'statut_participant' => 1,
    ];
});
