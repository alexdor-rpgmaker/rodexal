<?php

use App\User;
use App\PreTest;
use Faker\Generator as Faker;

$factory->define(App\PreTest::class, function (Faker $faker) {
    return [
        'user_id' => User::inRandomOrder()->first()->id,
        'game_id' => $faker->numberBetween(5, 25),
        'questionnaire' => [
            'blockingBug' => [
                'activated' => $faker->boolean,
                'explanation' => $faker->text
            ],
            'notAutonomous' => [
                'activated' => $faker->boolean,
                'explanation' => $faker->text
            ],
            'notLaunchable' => [
                'activated' => $faker->boolean,
                'explanation' => $faker->text
            ],
            'severalBugs' => [
                'activated' => $faker->boolean,
                'explanation' => $faker->text
            ],
            'spellingMistakes' => [
                'activated' => $faker->boolean,
                'explanation' => $faker->text
            ],
            'tooHard' => [
                'activated' => $faker->boolean,
                'explanation' => $faker->text
            ],
            'tooShort' => [
                'activated' => $faker->boolean,
                'explanation' => $faker->text
            ],
            'unplayableAlone' => [
                'activated' => $faker->boolean,
                'explanation' => $faker->text
            ]
        ],
        'final_thought' => $faker->boolean,
        'final_thought_explanation' => $faker->paragraphs($faker->numberBetween(1, 3), true)
    ];
});