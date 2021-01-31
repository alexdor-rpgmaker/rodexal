<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestAverageCharacter extends FormerModel
{
    use HasFactory;

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
