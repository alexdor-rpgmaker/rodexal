<?php

namespace App\Former;

class JukeboxMusic extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'jukebox';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_zik' => 1
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_publication',
    ];

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu_origine');
    }

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_posteur');
    }
}
