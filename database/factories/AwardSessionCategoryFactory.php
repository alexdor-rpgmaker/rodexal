<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Session;
use App\Former\AwardGeneralCategory;
use Faker\Generator as Faker;

$factory->define(App\Former\AwardSessionCategory::class, function (Faker $faker) {
    return [
        'id_serie_categorie' => factory(AwardGeneralCategory::class),
        'nom_categorie' => $faker->words(3, true),
        'description_categorie' => $faker->paragraph(2),
        'niveau_categorie' => $faker->numberBetween(1, 4),
        'id_session' => factory(Session::class),
        'date_ajout_categorie' => now(),
        'is_declinaison' => $faker->boolean,
        'is_after_tests' => $faker->boolean,
        'ordre' => $faker->numberBetween(1, 20)
    ];
});
