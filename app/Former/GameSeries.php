<?php

namespace App\Former;

use App\Helpers\StringParser;
use Illuminate\Database\Eloquent\Builder;

class GameSeries extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'series_jeux';
    /**
     * @var string
     */
    protected $primaryKey = 'id_serie';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'is_serie' => false,
        'is_meme_jeu' => false,
        'is_repost' => false,
        'trop_peu_donnees' => false,
    ];

    public function games()
    {
        return $this->hasMany('App\Former\Game', 'id_serie_jeu');
    }
}
