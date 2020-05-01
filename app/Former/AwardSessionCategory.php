<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Builder;

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

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('deleted', function (Builder $builder) {
            $builder->where('statut_categorie', '>', 0);
        });
    }
}
