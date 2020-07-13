<?php /** @noinspection PhpUndefinedVariableInspection */

use Faker\Generator as Faker;

$factory->define(App\Former\CarouselImage::class, function (Faker $faker) {
    return [
        'url_image' => "uploads/carrousel/id-" . mt_rand(1111, 3333) . ".png",
        'alt' => $faker->words(3, true),
        'description' => $faker->sentence,
    ];
});
