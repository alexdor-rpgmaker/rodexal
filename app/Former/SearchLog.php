<?php

namespace App\Former;

class SearchLog extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'recherches';
    /**
     * @var string
     */
    protected $primaryKey = 'id_recherche';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'nb_resultats' => 0,
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date_recherche' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo('App\Former\Member', 'id_membre');
    }

    public static function getFormUrl(): string
    {
        $formerAppUrl = env('FORMER_APP_URL');
        return "$formerAppUrl/?p=recherche";
    }
}
