<?php

namespace App\Former;

class TestAverageCharacter extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'tests_average_char';
    /**
     * @var string
     */
    protected $primaryKey = 'id_tests_average_char';

    public function test()
    {
        return $this->belongsTo('App\Former\Test', 'id_test');
    }
}
