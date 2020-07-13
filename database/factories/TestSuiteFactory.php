<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Session;
use Faker\Generator as Faker;

$factory->define(App\Former\TestSuite::class, function (Faker $faker) {
    return [
        'nom_serie' => array_rand(['Tests', 'Pré-tests', 'QCM']),
        'description_serie' => $faker->paragraph(2),
        'id_session' => factory(Session::class),
    ];
});
