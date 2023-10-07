<?php

namespace App\Former;

class GuestbookPost extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'guestbook';
    /**
     * @var string
     */
    protected $primaryKey = 'id_signature';
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date_signature' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
