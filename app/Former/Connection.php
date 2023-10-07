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
    protected $casts = [
        'date_connexion' => 'datetime',
        'date_expiration' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
