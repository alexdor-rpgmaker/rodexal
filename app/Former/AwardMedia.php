<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AwardMedia extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'awards_medias';
    /**
     * @var string
     */
    protected $primaryKey = 'id_media';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_media' => 2,
        'type_media' => 1
    ];
    protected $casts = [
        'date_ajout_media' => 'datetime',
    ];

    public function artist()
    {
        return $this->belongsTo('App\Former\Member', 'id_artiste');
    }

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }

    public function category()
    {
        return $this->belongsTo('App\Former\AwardSessionCategory', 'id_categorie');
    }

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('deleted', function (Builder $builder) {
            $builder->where('statut_media', '>', 0);
        });
    }
}
