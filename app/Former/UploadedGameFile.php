<?php

namespace App\Former;

class UploadedGameFile extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'jeux_favoris';
    /**
     * @var string
     */
    protected $primaryKey = 'id_jeu_upload';
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date_upload' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
