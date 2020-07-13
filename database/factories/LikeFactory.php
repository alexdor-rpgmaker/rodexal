<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Game;
use App\Former\Member;

use Faker\Generator as Faker;

$factory->define(App\Former\Like::class, function (Faker $faker) {
    return [
        'id_jeu' => factory(Game::class),
        'id_membre' => factory(Member::class),
        'date_modification' => now(),
    ];
});
