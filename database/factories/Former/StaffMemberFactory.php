<?php

namespace Database\Factories\Former;

use App\Former\Member;
use App\Former\Session;
use App\Former\StaffMember;

use Illuminate\Database\Eloquent\Factories\Factory;

class StaffMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StaffMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type_role = mt_rand(1, 8);
        $type_to_role = [
            1 => 'Président',
            2 => 'Chef des jurés',
            3 => 'Responsable du site web',
            4 => 'Responsable de la communication',
            5 => 'Ambassadeur',
            6 => 'Illustrateur',
            7 => 'Aide',
            8 => 'Meilleur juré',
        ];

        return [
            'id_membre' => Member::factory(),
            'id_session' => Session::factory(),
            'role' => $type_to_role[$type_role],
            'type_role' => $type_role,
            'ordre' => mt_rand(1, 10),
        ];
    }
}
