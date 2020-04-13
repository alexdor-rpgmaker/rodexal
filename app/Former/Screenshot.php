<?php

namespace App\Former;

use App\Helpers\StringParser;

class Screenshot extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'screenshots';
    /**
     * @var string
     */
    protected $primaryKey = 'id_screenshot';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_screenshot' => 1,
    ];

    public function game()
    {
        return $this->belongsTo('App\Former\Game', 'id_jeu');
    }

    public function getImageUrlForSession($sessionId): string
    {
        if ($this->local) {
            $sessionName = Session::nameFromId($sessionId);
            return env('FORMER_APP_URL') . '/uploads/screenshots/' . $sessionName . "/" . StringParser::html($this->local);
        }

        return $this->distant;
    }
}
