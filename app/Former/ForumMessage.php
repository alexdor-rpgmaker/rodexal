<?php

namespace App\Former;

class ForumMessage extends FormerModel
{
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
    protected $dates = [
        'date_publication',
        'date_edition',
        'date_dernier_message',
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
