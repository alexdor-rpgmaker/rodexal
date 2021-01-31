<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nomination extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'nomines';

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }

    public function category()
    {
        return $this->belongsTo('App\Former\AwardSessionCategory', 'id_categorie');
    }
}
