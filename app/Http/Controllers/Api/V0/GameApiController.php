<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\V0;

use App\Former\Game;
use App\Former\Session;
use App\Helpers\StringParser;
use App\Http\Controllers\Controller;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class GameApiController extends Controller
{
    public function index(Request $request)
    {
        // TODO : Check N+1
        $games = Game::with(['session', 'contributors.member', 'screenshots', 'awards'])
            ->withCount(['nominations as awarded_categories_count' => function($nominationsQuery) {
                $nominationsQuery->where('is_vainqueur', '>', '0');
            }])
            ->withCount(['nominations as nominated_categories_count' => function($nominationsQuery) {
                $nominationsQuery->where('is_vainqueur', '0');
            }]);

        if ($request->session_id) {
            if (!Session::sessionIdExists($request->session_id)) {
                abort(400, "This session does not exist");
            }

            $games = $games->where('id_session', $request->session_id);
        }

        if ($request->software) {
            $games = $games->where('support', $request->software);
        }

        if ($request->q) {
            $searchedTerm = "%" . $request->q . "%";

            $games = $games->where(function ($query) use ($searchedTerm) {
                $query->where('nom_jeu', 'like', $searchedTerm);
                $query->orWhere('description_jeu', 'like', $searchedTerm);
                $query->orWhere('genre_jeu', 'like', $searchedTerm);
                $query->orWhere('theme', 'like', $searchedTerm);
                $query->orWhere('support', 'like', $searchedTerm);
                $query->orWhere('groupe', 'like', $searchedTerm);
            });
        }

        if ($request->sort) {
            $games = $this->sortGamesFromQuery($games, $request->sort);
        }

        $PER_PAGE_DEFAULT = 50;
        $perPage = isset($request->per_page) && $this->between1And50($request->per_page) ? (int)$request->per_page : $PER_PAGE_DEFAULT;

        $games = $games->orderBy('id_session')
            ->orderBy('id_jeu')
            ->paginate($perPage);

        $games->getCollection()->transform(function ($game) {
            $game = $this->cleanAttributes($game, [
                'nom_jeu', 'taille', 'genre_jeu', 'theme', 'duree', 'support', 'site_officiel', 'groupe'
            ]);

            return [
                'id' => $game->id_jeu,
                'status' => $game->getStatus(),
                'title' => $game->nom_jeu,
                'session' => [
                    'id' => $game->session->id_session,
                    'name' => $game->session->nom_session
                ],
                'genre' => $game->genre_jeu,
                'software' => $game->support,
                'theme' => $game->theme,
                'duration' => $game->duree,
                'size' => $game->poids,
                'website' => $game->site_officiel,
                'creation_group' => $game->groupe,
                'logo' => $game->getLogoUrl(),
                'created_at' => $this->formatDateOrNullify($game->date_inscription),
                'description' => $game->description_jeu,
                'download_links' => $this->extractDownloadLinks($game),
                'awards' => $game->awards->transform($this->parseAwards()),
                'authors' => $game->contributors->transform($this->parseContributors()),
                'screenshots' => $game->screenshots->transform($this->parseScreenshots($game))
            ];
        });
        return response()->json($games, 200);
    }

    private function extractDownloadLinks($game)
    {
        $downloadLinks = [];
        if (!empty($game->lien_sur_site) || !empty($game->lien)) {
            $downloadLinks[] = [
                'platform' => 'windows',
                'url' => !empty($game->lien_sur_site) ? $this->onWebsiteDownloadUrl($game, 'lien_sur_site') : $game->lien,
            ];
        }
        if (!empty($game->lien_sur_site_sur_mac) || !empty($game->lien_sur_mac)) {
            $downloadLinks[] = [
                'platform' => 'mac',
                'url' => !empty($game->lien_sur_site_sur_mac) ? $this->onWebsiteDownloadUrl($game, 'lien_sur_site_sur_mac') : $game->lien_sur_mac,
            ];
        }

        return $downloadLinks;
    }

    private function onWebsiteDownloadUrl($game, $downloadColumn)
    {
        $downloadUrl = env('FORMER_APP_URL') . '/archives/';
        $downloadUrl .= Session::nameFromId($game->session->id_session);
        $downloadUrl .= '/jeux/' . StringParser::html($game->{$downloadColumn});
        return $downloadUrl;
    }

    /**
     * @return Closure
     */
    private function parseContributors(): Closure
    {
        return function ($contributor) {
            $userId = $contributor->id_membre ? $contributor->id_membre : null;
            $rank = $userId && $contributor->member ? $contributor->member->rank : null;
            $username = $userId && $contributor->member ? $contributor->member->pseudo : $contributor->nom_membre;

            return [
                'id' => $userId,
                'rank' => $rank,
                'username' => StringParser::html($username),
                'role' => $this->parseOrNullify($contributor->role)
            ];
        };
    }

    /**
     * @param $game
     * @return Closure
     */
    private function parseScreenshots($game): Closure
    {
        return function ($screenshot) use ($game) {
            return [
                'title' => $this->parseOrNullify($screenshot->nom_screenshot),
                'url' => $screenshot->getImageUrlForSession($game->session->id_session),
            ];
        };
    }

    /**
     * @return Closure
     */
    private function parseAwards(): Closure
    {
        return function ($award) {
            $awardLevel = null;
            if ($award->is_declinaison) {
                switch ($award->pivot->is_vainqueur) {
                    case 1:
                        $awardLevel = 'gold';
                        break;
                    case 2:
                        $awardLevel = 'silver';
                        break;
                    case 3:
                        $awardLevel = 'bronze';
                        break;
                }
            }

            return [
                'status' => $award->pivot->is_vainqueur > 0 ? 'awarded' : 'nominated',
                'award_level' => $awardLevel,
                'category_name' => StringParser::html($award->nom_categorie)
            ];
        };
    }

    private function between1And50($number)
    {
        return in_array(intval($number), range(1, 50));
    }

    private function parseOrNullify($string)
    {
        return empty($string) ? null : StringParser::html($string);
    }

    private function formatDateOrNullify($date)
    {
        return empty($date) ? null : $date->format('c');
    }

    private function cleanAttributes($object, $attributes)
    {
        foreach ($attributes as $attribute) {
            $object->{$attribute} = $this->parseOrNullify($object->{$attribute});
        }
        return $object;
    }

    /**
     * @param Builder $games
     * @param string $sort
     * @return Builder
     */
    private function sortGamesFromQuery(Builder $games, string $sort): Builder
    {
        $columnNamesConverter = array_flip([
            'id_jeu' => 'id',
            'statut_jeu' => 'status',
            'nom_jeu' => 'title',
            'id_session' => 'session',
            'genre_jeu' => 'genre',
            'support' => 'software',
            'duree' => 'duration',
            'poids' => 'size',
            'site_officiel' => 'website',
            'groupe' => 'creation_group',
            'date_inscription' => 'created_at',
            'awards_count' => 'awards_count',
        ]);

        $DEFAULT_SORT_COLUMN = 'created_at';
        $DEFAULT_SORT_DIRECTION = 'asc';

        $sortingSegments = explode(',', $sort);
        foreach ($sortingSegments as $sortingSegment) {
            if (empty($sortingSegment)) {
                continue;
            }

            list($columnToSort, $sortingDirection) = array_pad(explode(":", $sortingSegment), 2, null);

            if (!$columnToSort) {
                $columnToSort = $DEFAULT_SORT_COLUMN;
            }

            if (!array_key_exists($columnToSort, $columnNamesConverter)) {
                continue;
            }

            if (!$sortingDirection) {
                $sortingDirection = $DEFAULT_SORT_DIRECTION;
            }

            if (array_key_exists($columnToSort, $columnNamesConverter)) {
                $columnToSort = $columnNamesConverter[$columnToSort];
            }

            if ($columnToSort == 'awards_count') {
                $games = $games->orderBy('awarded_categories_count', $sortingDirection)
                    ->orderBy('nominated_categories_count', $sortingDirection);
            } else {
                $games = $games->orderBy($columnToSort, $sortingDirection);
            }
        }

        return $games;
    }
}
