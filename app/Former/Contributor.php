<?php

namespace App\Former;

class Contributor extends FormerModel
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

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }

    public function getLinkOrNameAttribute()
    {
        if ($this->member) {
            return $this->member->getLink();
        }
        return $this->nom_membre;
    }
}
