<?php /** @noinspection PhpUndefinedVariableInspection */

use Faker\Generator as Faker;

$factory->define(App\Former\AwardGeneralCategory::class, function (Faker $faker) {
    return [
        'nom_serie' => $faker->words(3, true),
        'description_serie' => $faker->paragraph(2)
    ];
});
