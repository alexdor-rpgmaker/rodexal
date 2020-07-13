<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Member;
use App\Former\ForumSection;

use Faker\Generator as Faker;

$factory->define(App\Former\ForumMessage::class, function (Faker $faker) {
    return [
        'statut_message' => 1,
        'type_message' => mt_rand(0, 2),
        'id_membre' => factory(Member::class),
        'id_forum' => factory(ForumSection::class),
        'titre_message' => $faker->words(3, true),
        'sous_titre_message' => $faker->words(3, true),
        'date_publication' => now(),
    ];
});
