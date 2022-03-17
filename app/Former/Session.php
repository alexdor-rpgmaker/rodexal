<?php

namespace App\Former;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends FormerModel
{
    use HasFactory;

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
        'etape' => 1,
    ];
    protected $dates = [
        'date_cloture_inscriptions',
    ];

    const IDS_SESSIONS_WITH_QCM = [19, 20, 21, 22];

    public function allowsGamesRegistration(): bool
    {
        // TODO : Add a specific column for this rule
        return $this->etape == 1;
    }

    public function tooLateForGamesRegistration(): bool
    {
        // TODO : Add a specific column for this rule
        return $this->etape > 1;
    }

    public function preTestsAreFinished(): bool
    {
        // TODO : Add a specific column for this rule
        return $this->etape > 2;
    }

    public function lastIncludedDayForGamesRegistration(): string
    {
        return $this->date_cloture_inscriptions->subDay()->format('d/m/Y');
    }

    public function gamesRegistrationEndsInLessThanSevenDays(): bool
    {
        $absolute = false;
        $daysBeforeGamesRegistrationEnd = Carbon::now()->diffInDays($this->date_cloture_inscriptions, $absolute);
        return $daysBeforeGamesRegistrationEnd >= 0 && $daysBeforeGamesRegistrationEnd < 7;
    }

    public function name(): string
    {
        return self::nameFromId($this->id_session);
    }

    public static function nameFromId($sessionId): string
    {
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

    public static function sessionIdExists($sessionId, $options = ['include_abandoned_sessions' => true]): bool
    {
        if (
            $sessionId < 1 ||
            $sessionId == 4 ||
            $sessionId == 18 ||
            $sessionId > 22 # TODO : Make variable?
        ) {
            return false;
        }

        if (!$options['include_abandoned_sessions'] && $sessionId == 8) {
            return false;
        }

        return true;
    }
}
