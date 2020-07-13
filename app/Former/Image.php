<?php

namespace App\Former;

class Image extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'images';
    /**
     * @var string
     */
    protected $primaryKey = 'id_image';
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_upload',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }
}
