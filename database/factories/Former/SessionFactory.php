<?php

namespace Database\Factories\Former;

use App\Former\Session;

use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'statut_session' => 1,
            'nom_session' => 'Session '.(2000 + $this->faker->numberBetween(1, 20)),
            'etape' => $this->faker->numberBetween(1, 5),
            'date_cloture_inscriptions' => $this->faker->date(),
        ];
    }
}
