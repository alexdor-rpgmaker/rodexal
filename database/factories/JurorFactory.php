<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Member;
use App\Former\Session;

use Faker\Generator as Faker;

$factory->define(App\Former\Juror::class, function (Faker $faker) {
    return [
        'id_membre' => factory(Member::class),
        'id_session' => factory(Session::class),
        'statut_jury' => 2,
        'date_inscription' => now(),
    ];
});
