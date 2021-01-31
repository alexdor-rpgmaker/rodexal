<?php

namespace Database\Factories\Former;

use App\Former\Member;

use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Member::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'pseudo' => $this->faker->userName,
            'passe' => $this->faker->password,
            'mail' => $this->faker->email,
            'statut_membre' => 1,
            'date_inscription' => now(),
        ];
    }
}
