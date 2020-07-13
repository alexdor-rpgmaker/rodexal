<?php

namespace App\Former;

class Juror extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'jury';
    /**
     * @var string
     */
    protected $primaryKey = 'id_jury';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'groupe' => 0,
        'statut_jury' => 2,
        'is_chef_groupe' => false
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_inscription',
        'date_validation',
    ];

    public function session()
    {
        return $this->belongsTo('App\Former\Session', 'id_session');
    }

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
