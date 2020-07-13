<?php

namespace App\Former;

class StaffMember extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'equipe';
    /**
     * @var string
     */
    protected $primaryKey = 'id_equipe';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'pseudo' => null,
        'type_role' => 7,
        'ordre' => 1
    ];

    public function session()
    {
        return $this->belongsTo('App\Former\Session', 'id_session');
    }

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
