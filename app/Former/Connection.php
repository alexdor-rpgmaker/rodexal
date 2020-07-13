<?php

namespace App\Former;

class Connection extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'connexions';
    /**
     * @var string
     */
    protected $primaryKey = 'id_connexion';
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_connexion',
        'date_expiration',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
