<?php

namespace App\Former;

class MemberReadMessage extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'lus';
    /**
     * @var string
     */
    protected $primaryKey = 'id_lu';

    public function section()
    {
        return $this->belongsTo('App\Former\ForumSection', 'id_forum');
    }

    public function message()
    {
        return $this->belongsTo('App\Former\ForumMessage', 'id_dernier_message_lu');
    }
}
