<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'commentaires';
    /**
     * @var string
     */
    protected $primaryKey = 'id_commentaire';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_commentaire' => 1,
        'nombre_edition' => 0,
        'type_parent' => 'News',
        'is_entre_jury' => false,
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date_publication' => 'datetime',
        'date_edition' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }

    // TODO : Deal with Morph associations
    public function newsPost()
    {
        return $this->belongsTo('App\Former\NewsPost', 'id_parent');
    }

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }
}
