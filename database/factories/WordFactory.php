<?php

use App\User;
use App\Word;
use Faker\Generator as Faker;

$factory->define(App\Word::class, function (Faker $faker) {
    $label = preg_replace('/([a-z]+)([A-Z])/', '$1 $2', $faker->unique()->colorName);
    return [
        'label' => $label,
        'slug' => str_slug($label, '-'),
        'user_id' => User::inRandomOrder()->first()->id,
        'description' => $faker->unique()->paragraphs(mt_rand(1, 3), true)
    ];
});