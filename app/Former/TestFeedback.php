<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestFeedback extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tests_feedbacks';

    public function test()
    {
        return $this->belongsTo('App\Former\Test', 'id_test');
    }
}
