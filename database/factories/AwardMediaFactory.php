<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Game;
use App\Former\Member;
use App\Former\AwardSessionCategory;
use Faker\Generator as Faker;

$factory->define(App\Former\AwardMedia::class, function (Faker $faker) {
    return [
        'id_jeu' => factory(Game::class),
        'id_artiste' => factory(Member::class),
        'id_categorie' => factory(AwardSessionCategory::class),
        'date_ajout_media' => now(),
        'is_placeholder' => false
    ];
});
