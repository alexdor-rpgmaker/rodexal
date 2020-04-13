<?php

namespace App\Former;

class AwardSessionCategory extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'awards_categories';
    /**
     * @var string
     */
    protected $primaryKey = 'id_categorie';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_categorie' => 1
    ];

    public function generalCategory()
    {
        return $this->belongsTo('App\Former\AwardGeneralCategory', 'id_serie', 'id_serie_categorie');
    }

    public function session()
    {
        return $this->belongsTo('App\Former\Session', 'id_session');
    }
}
