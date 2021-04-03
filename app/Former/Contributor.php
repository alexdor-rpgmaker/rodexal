<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contributor extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'participants';
    /**
     * @var string
     */
    protected $primaryKey = 'id_participants';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'ordre' => 1,
        'peut_editer_jeu' => 1,
        'statut_participant' => 1
    ];

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
