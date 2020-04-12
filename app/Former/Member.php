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
}
