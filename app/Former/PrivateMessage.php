<?php

namespace App\Former;

class PrivateMessage extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'mp';
    /**
     * @var string
     */
    protected $primaryKey = 'id_message';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'lu' => false,
        'nombre_edition' => 0,
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_publication',
        'date_edition',
        'date_dernier_message',
    ];

    public function sender()
    {
        return $this->belongsTo('App\Former\Member', 'id_destinateur');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Former\Member', 'id_destinataire');
    }
}
