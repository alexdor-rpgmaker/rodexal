<?php /** @noinspection PhpUndefinedVariableInspection */

use App\Former\Game;
use App\Former\AwardSessionCategory;
use Faker\Generator as Faker;

$factory->define(App\Former\Nomination::class, function (Faker $faker) {
    return [
        'id_jeu' => factory(Game::class),
        'id_categorie' => factory(AwardSessionCategory::class),
        'is_vainqueur' => $faker->numberBetween(0, 4)
    ];
});
