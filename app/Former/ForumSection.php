<?php

namespace App\Former;

class ForumSection extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'forum_forums';
    /**
     * @var string
     */
    protected $primaryKey = 'id_forum';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_forum' => 1,
        'position_forum' => 1,
        'permission_forum' => 0,
        'nombre_sujets' => 0,
        'nombre_messages' => 0,
        'id_dernier_message_forum' => null,
        'parent_forum_id' => null,
    ];

    public function category()
    {
        return $this->belongsTo('App\Former\ForumCategory', 'id_categorie');
    }

    public function parentSection()
    {
        return $this->belongsTo('App\Former\ForumSection', 'parent_forum_id');
    }
}
