<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\V0;

use App\Former\Game;
use App\Former\Session;
use App\Helpers\StringParser;
use App\Http\Controllers\Controller;

use Closure;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
    public function index(Request $request)
    {
        // TODO : Check N+1
        $games = Game::with(['session', 'contributors.member', 'screenshots', 'awards']);

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

        $games = $games->paginate(30);

        $games->getCollection()->transform(function ($game) {
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
                'created_at' => $game->date_inscription->format('c'),
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
            $username = $contributor->id_membre ? $contributor->member->pseudo : $contributor->nom_membre;

            return [
                'id' => $contributor->id_membre,
                'username' => StringParser::html($username),
                'role' => $contributor->role
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
                'title' => StringParser::html($screenshot->nom_screenshot),
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
}
