<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Test;
use Faker\Generator as Faker;

$factory->define(App\Former\TestAverageCharacter::class, function (Faker $faker) {
    return [
        'id_test' => factory(Test::class),
        'average_char' => mt_rand(100, 9999),
    ];
});
