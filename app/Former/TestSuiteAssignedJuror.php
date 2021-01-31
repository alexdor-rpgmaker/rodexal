<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestSuiteAssignedJuror extends FormerModel
{
    use HasFactory;

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
