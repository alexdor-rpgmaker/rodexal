<?php

namespace App\Former;

class TestSuite extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'series_tests';
    /**
     * @var string
     */
    protected $primaryKey = 'id_serie';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_serie' => 1,
        'is_locked' => false,
        'is_pre_test' => false,
        'is_published' => false,
        'is_published_for_jury' => false,
    ];
}
