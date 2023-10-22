<?php

namespace Database\Factories;

use App\User;
use App\Word;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Word::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // colorName() returns something like 'SeaGreen' or 'DarkOliveGreen'
        $label = preg_replace('/([a-z]+)([A-Z])/', '$1 $2', $this->faker->unique()->colorName);
        $label .= " " . $this->faker->randomNumber(3, true);

        return [
            'label' => $label,
            'slug' => Str::slug($label, '-'),
            'user_id' => User::inRandomOrder()->first()->id,
            'description' => $this->faker->unique()->paragraphs(mt_rand(1, 3), true),
        ];
    }
}
