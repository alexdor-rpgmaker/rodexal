<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Juror extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'jury';
    /**
     * @var string
     */
    protected $primaryKey = 'id_jury';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'groupe' => 0,
        'statut_jury' => 2,
        'is_chef_groupe' => false
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date_inscription' => 'date',
        'date_validation' => 'date',
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
