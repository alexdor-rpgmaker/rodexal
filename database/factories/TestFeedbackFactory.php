<?php

namespace Database\Factories;

use App\Former\Test;
use App\Former\Member;
use App\Former\TestFeedback;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestFeedbackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TestFeedback::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_test' => Test::factory(),
            'id_membre' => Member::factory(),
            'note' => mt_rand(1, 5),
            'date' => now(),
        ];
    }
}
