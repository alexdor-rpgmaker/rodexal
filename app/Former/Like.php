<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends FormerModel
{
    use HasFactory;

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
    protected $casts = [
        'date_modification' => 'datetime',
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
