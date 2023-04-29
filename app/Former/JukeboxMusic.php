<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class JukeboxMusic extends FormerModel
{
    use HasFactory;

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
