<?php

namespace App\Former;

class TestSuiteAssignedJuror extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'series_tests_jeux_jures';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_jeu_jure' => 2,
    ];

    public function suite()
    {
        return $this->belongsTo('App\Former\TestSuite', 'id_serie');
    }

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }

    public function juror()
    {
        return $this->belongsTo('App\Former\Juror', 'id_jury');
    }
}
