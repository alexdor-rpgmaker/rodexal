<?php

namespace Database\Factories;

use App\Former\Session;
use App\Former\TestSuite;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestSuiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TestSuite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom_serie' => array_rand(['Tests', 'Pré-tests', 'QCM']),
            'description_serie' => $this->faker->paragraph(2),
            'id_session' => Session::factory(),
        ];
    }
}
