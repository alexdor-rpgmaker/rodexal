<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreTest extends Model
{
    protected $table = 'pre_tests';

    protected $casts = [
        'questionnaire' => 'array'
    ];
}
