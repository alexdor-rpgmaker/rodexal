<?php

namespace Database\Factories;

use App\Former\Test;
use App\Former\TestAverageCharacter;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestAverageCharacterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TestAverageCharacter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_test' => Test::factory(),
            'average_char' => mt_rand(100, 9999),
        ];
    }
}
