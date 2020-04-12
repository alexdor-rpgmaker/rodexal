<?php

namespace App\Former;

use App\Helpers\StringParser;

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
        'statut_jeu' => '1',
    ];

    public function session()
    {
        return $this->belongsTo('App\Former\Session', 'id_session');
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

    public function logoUrl(): string {
        if (!empty($this->logo)) {
            return env('FORMER_APP_URL') . '/uploads/logos/' . $this->logo;
        } else if (!empty($this->logo_distant)) {
            return StringParser::html($this->logo_distant);
        }

        return null;
    }
}
