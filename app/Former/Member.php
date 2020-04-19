<?php

namespace App\Former;

class Member extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'membres';
    /**
     * @var string
     */
    protected $primaryKey = 'id_membre';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_membre' => 1,
        'rang' => 1,
        'sexe' => 0,
    ];

    public function getRankAttribute() {
        $rankIdToString = [
            0 => "guest",
            1 => "member",
            2 => "challenger",
            3 => "ambassador",
            4 => "juror",
            5 => "moderator",
            6 => "administrator",
            7 => "webmaster"
        ];

        return $rankIdToString[$this->rang];
    }
}
