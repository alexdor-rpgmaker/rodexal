<?php

namespace App\Former;

use App\Helpers\StringParser;
use Illuminate\Database\Eloquent\Builder;

class Game extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'jeux';
    /**
     * @var string
     */
    protected $primaryKey = 'id_jeu';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_jeu' => 1
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $dates = [
        'date_inscription',
    ];

    public function session()
    {
        return $this->belongsTo('App\Former\Session', 'id_session');
    }

    public function contributors()
    {
        return $this->hasMany('App\Former\Contributor', 'id_jeu')->where('statut_participant', '>', 0);
    }

    public function screenshots()
    {
        return $this->hasMany('App\Former\Screenshot', 'id_jeu')->where('statut_screenshot', '>', 0);
    }

    public function nominations()
    {
        return $this->hasMany('App\Former\Nomination', 'id_jeu');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Former\AwardSessionCategory', 'nomines', 'id_jeu', 'id_categorie')->withPivot('is_vainqueur')->where('statut_categorie', '>', 0);
    }

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('deleted', function (Builder $builder) {
            $builder->where('statut_jeu', '>', 0);
        });
    }

    public function getStatus(): string
    {
        $stepStatusMatrix = [
            1 => [
                0 => 'deleted',
                1 => 'applied',
            ],
            2 => [
                0 => 'deleted',
                1 => 'applied',
            ],
            3 => [
                0 => 'deleted',
                1 => 'not_qualified',
                2 => 'qualified',
            ],
            4 => [
                0 => 'deleted',
                1 => 'not_qualified',
                2 => 'not_nominated',
                3 => 'nominated',
            ],
            5 => [
                0 => 'deleted',
                1 => 'not_qualified',
                2 => 'not_nominated',
                3 => 'not_awarded',
                4 => 'awarded',
            ],
        ];

        return $stepStatusMatrix[$this->session->etape][$this->statut_jeu];
    }

    /**
     * @return string|null
     */
    public function getLogoUrl()
    {
        if (!empty($this->logo)) {
            return env('FORMER_APP_URL') . '/uploads/logos/' . $this->logo;
        } else if (!empty($this->logo_distant)) {
            return StringParser::html($this->logo_distant);
        }

        return null;
    }
}
