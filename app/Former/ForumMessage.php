<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumMessage extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'forum_messages';
    /**
     * @var string
     */
    protected $primaryKey = 'id_message';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'type_message' => 0,
        'nombre_edition' => 0,
        'nombre_reponses' => 0,
        'nombre_visites' => 0,
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date_publication' => 'datetime',
        'date_edition' => 'datetime',
        'date_dernier_message' => 'datetime',
    ];

    public function section()
    {
        return $this->belongsTo('App\Former\ForumSection', 'id_forum');
    }

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
