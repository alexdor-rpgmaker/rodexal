<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestSuiteAssignedGame extends FormerModel
{
    use HasFactory;

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
