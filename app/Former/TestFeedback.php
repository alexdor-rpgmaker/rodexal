<?php

namespace App\Former;

class TestFeedback extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'tests_feedbacks';

    public function test()
    {
        return $this->belongsTo('App\Former\Test', 'id_test');
    }
}
