<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Test;
use App\Former\Member;
use Faker\Generator as Faker;

$factory->define(App\Former\TestFeedback::class, function (Faker $faker) {
    return [
        'id_test' => factory(Test::class),
        'id_membre' => factory(Member::class),
        'note' => mt_rand(1, 5),
        'date' => now(),
    ];
});
