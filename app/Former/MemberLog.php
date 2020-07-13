<?php

namespace App\Former;

class MemberLog extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'recap';
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_action',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
