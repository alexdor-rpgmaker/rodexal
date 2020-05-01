<?php

use Illuminate\Support\Str;

use App\User;
use App\Word;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Word::class, function (Faker $faker) {
    $label = preg_replace('/([a-z]+)([A-Z])/', '$1 $2', $faker->unique()->colorName);
    return [
        'label' => $label,
        'slug' => Str::slug($label, '-'),
        'user_id' => User::inRandomOrder()->first()->id,
        'description' => $faker->unique()->paragraphs(mt_rand(1, 3), true)
    ];
});
