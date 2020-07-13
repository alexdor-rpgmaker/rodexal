<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Member;
use Faker\Generator as Faker;

$factory->define(App\Former\NewsPost::class, function (Faker $faker) {
    return [
        'nom_news' => $faker->words(3, true),
        'contenu_news' => $faker->paragraphs(2, true),
        'id_membre' => factory(Member::class),
        'date_creation_news' => now()
    ];
});
