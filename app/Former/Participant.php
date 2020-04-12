<?php

namespace App\Former;

class Participant extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'participants';
    /**
     * @var string
     */
    protected $primaryKey = 'id_participants';

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }
}
