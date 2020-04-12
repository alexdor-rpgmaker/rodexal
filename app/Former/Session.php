<?php

namespace App\Former;

class Session extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'sessions';
    /**
     * @var string
     */
    protected $primaryKey = 'id_session';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_session' => '1',
        'etape' => '1',
    ];
}
