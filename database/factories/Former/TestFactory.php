<?php

namespace Database\Factories\Former;

use App\Former\Test;
use App\Former\Member;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Test::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'reviewer_id' => Member::factory(),
        ];
    }
}
