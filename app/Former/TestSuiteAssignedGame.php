<?php

namespace App\Former;

class TestSuiteAssignedGame extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'series_tests_jeux';

    public function suite()
    {
        return $this->belongsTo('App\Former\TestSuite', 'id_serie');
    }

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }
}
