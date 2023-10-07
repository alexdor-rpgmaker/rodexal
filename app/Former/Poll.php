<?php

namespace App\Former;

class Poll extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'sondages';
    /**
     * @var string
     */
    protected $primaryKey = 'id_sondage';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_sondage' => 1,
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date_sondage' => 'datetime',
    ];
}
