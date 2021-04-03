<?php

namespace App\Former;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends FormerModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'membres';
    /**
     * @var string
     */
    protected $primaryKey = 'id_membre';
    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = [
        'statut_membre' => 1,
        'rang' => 1,
        'sexe' => 0,
    ];

    const RANKS = [
        "Invité",
        "Membre",
        "Concurrent",
        "Ambassadeur",
        "Juré",
        "Modérateur",
        "Admin",
        "Webmaster"
    ];

    public function getRankAttribute(): string
    {
        $rankIdToString = [
            0 => "guest",
            1 => "member",
            2 => "challenger",
            3 => "ambassador",
            4 => "juror",
            5 => "moderator",
            6 => "administrator",
            7 => "webmaster"
        ];

        return $rankIdToString[$this->rang];
    }

    public function getLink(): string
    {
        $formerAppUrl = env('FORMER_APP_URL');

        return "<a " .
            "href=\"$formerAppUrl/?p=profil&membre=$this->id_membre\" " .
            "class=\"color-$this->rank\"" .
            ">$this->pseudo</a>";
    }
}
