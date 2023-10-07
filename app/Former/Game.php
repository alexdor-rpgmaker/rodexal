<?php

namespace App\Former;

use App\Helpers\StringParser;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends FormerModel
{
    use Notifiable;
    use HasFactory;

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
        'statut_jeu' => 'registered',
        'favori' => 0,
        'nb_commentaires' => 0
    ];
    /**
     * Attributes automatically parsed as dates.
     *
     * @var array
     */
    protected $casts = [
        'date_inscription' => 'datetime',
    ];

    const SOFTWARE_LIST = [
        'Clickteam Fusion 2.5',
        'Game Maker Studio 2',
        'RPG Maker 2003',
        'RPG Maker MV',
        'RPG Maker VX',
        'RPG Maker VX Ace',
        'RPG Maker XP',
        'Unity'
    ];

    public function session()
    {
        return $this->belongsTo('App\Former\Session', 'id_session');
    }

    public function series()
    {
        return $this->belongsTo('App\Former\GameSeries', 'id_serie_jeu');
    }

    public function contributors()
    {
        return $this->hasMany('App\Former\Contributor', 'id_jeu')->where('statut_participant', '>', 0)->orderBy('ordre');
    }

    public function screenshots()
    {
        return $this->hasMany('App\Former\Screenshot', 'id_jeu')->where('statut_screenshot', '>', 0)->orderBy('ordre');
    }

    public function nominations()
    {
        return $this->hasMany('App\Former\Nomination', 'id_jeu');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Former\AwardSessionCategory', 'nomines', 'id_jeu', 'id_categorie')->withPivot('is_vainqueur')->where('statut_categorie', '>', 0)->orderBy('niveau_categorie')->orderBy('ordre')->orderBy('is_declinaison');
    }

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('deleted', function (Builder $builder) {
            $builder->where('statut_jeu', '!=', 'deleted');
        });
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithWindowsDownloadLink($query)
    {
        return $query->where('link_removed_on_author_demand', false)
            ->where(function ($query) {
                $query->where('lien', '!=', '')
                    ->whereNotNull('lien')
                    ->where('is_lien_errone', false);
            })->orWhere(function ($query) {
                $query->where('lien_sur_site', '!=', '')
                    ->whereNotNull('lien_sur_site');
            });
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithMacDownloadLink($query)
    {
        return $query->where('link_removed_on_author_demand', false)
            ->where(function ($query) {
                $query->where('lien_sur_mac', '!=', '')
                    ->whereNotNull('lien_sur_mac')
                    ->where('is_lien_errone', false);
            })->orWhere(function ($query) {
                $query->where('lien_sur_site_sur_mac', '!=', '')
                    ->whereNotNull('lien_sur_site_sur_mac');
            });
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithoutDownloadLinks($query)
    {
        return $query->where('link_removed_on_author_demand', true)
            ->orWhere(function ($query) {
                $query->where(function ($query) {
                    $query->where('lien_sur_mac', '=', '')
                        ->orWhereNull('lien_sur_mac')
                        ->orWhere('is_lien_errone', true);
                })->where(function ($query) {
                    $query->where('lien_sur_site_sur_mac', '=', '')
                        ->orWhereNull('lien_sur_site_sur_mac');
                })->where(function ($query) {
                    $query->where('lien', '=', '')
                        ->orWhereNull('lien')
                        ->orWhere('is_lien_errone', true);
                })->where(function ($query) {
                    $query->where('lien_sur_site', '=', '')
                        ->orWhereNull('lien_sur_site');
                });
            });
    }

    public function getStatus(): string
    {
        // TODO : Remove ?
        $stepStatusMatrix = [
            1 => [
                'deleted' => 'deleted',
                'disqualified' => 'disqualified',
                'registered' => 'registered',
            ],
            2 => [
                'deleted' => 'deleted',
                'disqualified' => 'disqualified',
                'registered' => 'registered',
            ],
            3 => [
                'deleted' => 'deleted',
                'disqualified' => 'disqualified',
                'registered' => 'not_qualified',
                'qualified' => 'qualified',
            ],
            4 => [
                'deleted' => 'deleted',
                'disqualified' => 'disqualified',
                'registered' => 'not_qualified',
                'qualified' => 'not_nominated',
                'nominated' => 'nominated',
            ],
            5 => [
                'deleted' => 'deleted',
                'disqualified' => 'disqualified',
                'registered' => 'not_qualified',
                'qualified' => 'not_nominated',
                'nominated' => 'not_awarded',
                'awarded' => 'awarded',
            ],
        ];

        return $stepStatusMatrix[$this->session->etape][$this->statut_jeu];
    }

    /**
     * @return string|null
     */
    public function getLogoUrl(): ?string
    {
        if (!empty($this->logo)) {
            return env('FORMER_APP_URL') . '/uploads/logos/' . $this->logo;
        } else if (!empty($this->logo_distant)) {
            return StringParser::html($this->logo_distant);
        }

        return null;
    }

    public function hasWindowsDownloadLink(): bool
    {
        return !$this->link_removed_on_author_demand && (
                !empty($this->lien_sur_site) ||
                (!empty($this->lien) && !$this->is_lien_errone)
            );
    }

    public function hasMacDownloadLink(): bool
    {
        return !$this->link_removed_on_author_demand && (
                !empty($this->lien_sur_site_sur_mac) ||
                (!empty($this->lien_sur_mac) && !$this->is_lien_errone)
            );
    }

    public function getWindowsDownloadLink(): string
    {
        if (!empty($this->lien_sur_site)) {
            return $this->onWebsiteDownloadUrl('lien_sur_site');
        }

        return $this->lien;
    }

    public function getMacDownloadLink(): string
    {
        if (!empty($this->lien_sur_site_sur_mac)) {
            return $this->onWebsiteDownloadUrl('lien_sur_site_sur_mac');
        }

        return $this->lien_sur_mac;
    }

    private function onWebsiteDownloadUrl($downloadColumn): string
    {
        $downloadUrl = env('FORMER_APP_URL') . '/archives/';
        $downloadUrl .= Session::nameFromId($this->id_session);
        $downloadUrl .= '/jeux/' . StringParser::html($this->{$downloadColumn});
        return $downloadUrl;
    }

    public function getUrl(): string
    {
        $formerAppUrl = env('FORMER_APP_URL');
        return "$formerAppUrl/?p=jeu&id=$this->id_jeu";
    }

    public static function getListUrl($session = null): string
    {
        $formerAppUrl = env('FORMER_APP_URL');
        $url = "$formerAppUrl/?p=liste-jeux";
        return $session ? "$url&session=$session" : $url;
    }

    public function routeNotificationForDiscord()
    {
        return env('DISCORD_NEW_GAME_CHANNEL');
    }
}
