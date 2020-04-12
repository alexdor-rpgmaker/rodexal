<?php

namespace App\Former;

class Session extends FormerModel
{
    /**
     * @var string
     */
    protected $table = 'sessions';
    /**
     * @var string
     */
    protected $primaryKey = 'id_session';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_session' => 1,
        'etape' => '1',
    ];

    public static function nameFromId($sessionId) {
        $sessionName = ($sessionId < 10) ? '0' . $sessionId : $sessionId;
        if ($sessionId == 3) {
            $sessionName .= '-2004';
        }

        if ($sessionId == 16) {
            $sessionName .= '-2017';
        }

        if ($sessionId == 17) {
            $sessionName .= '-2018';
        }

        return "20" . $sessionName;
    }
}
