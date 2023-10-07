<?php

namespace App\Former;

class EmailSent extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'mail';
    /**
     * @var string
     */
    protected $primaryKey = 'id_mail';
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre_expediteur');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre_destinataire');
    }
}
