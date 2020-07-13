<?php

namespace App\Former;

class Like extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'jeux_favoris';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'favori' => true
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_modification',
    ];

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
