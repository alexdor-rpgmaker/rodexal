<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Member;
use App\Former\Session;

use Faker\Generator as Faker;

$factory->define(App\Former\StaffMember::class, function (Faker $faker) {
    $type_role = mt_rand(1, 8);
    $type_to_role = [
        1 => 'Président',
        2 => 'Chef des jurés',
        3 => 'Responsable du site web',
        4 => 'Responsable de la communication',
        5 => 'Ambassadeur',
        6 => 'Illustrateur',
        7 => 'Aide',
        8 => 'Meilleur juré',
    ];

    return [
        'id_membre' => factory(Member::class),
        'id_session' => factory(Session::class),
        'role' => $type_to_role[$type_role],
        'type_role' => $type_role,
        'ordre' => mt_rand(1, 10),
    ];
});
