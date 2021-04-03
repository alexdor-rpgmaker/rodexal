<?php

namespace Database\Factories\Former;

use App\Former\Juror;
use App\Former\Member;
use App\Former\Session;

use Illuminate\Database\Eloquent\Factories\Factory;

class JurorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Juror::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_membre' => Member::factory(),
            'id_session' => Session::factory(),
            'statut_jury' => 2,
            'date_inscription' => now(),
        ];
    }
}
