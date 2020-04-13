<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\V0;

use App\Former\Game;
use App\Former\Session;
use App\Helpers\StringParser;
use App\Http\Controllers\Controller;

class GameApiController extends Controller
{
    public function index()
    {
        // TODO : Check N+1
        $games = Game::with(['session', 'contributors.member', 'screenshots', 'awards'])->paginate(30);
        $games->getCollection()->transform(function ($game) {
            $authors = $game->contributors->transform(function ($contributor) {
                $username = $contributor->id_membre ? $contributor->member->pseudo : $contributor->nom_membre;

                return [
                    'id' => $contributor->id_membre,
                    'username' => StringParser::html($username),
                    'role' => $contributor->role
                ];
            });

            $screenshots = $game->screenshots->transform(function ($screenshot) use ($game) {
                return [
                    'title' => StringParser::html($screenshot->nom_screenshot),
                    'url' => $screenshot->getImageUrlForSession($game->session->id_session),
                ];
            });

            $awards = $game->awards->transform(function ($award) {
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
            });

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
                'authors' => $authors,
                'screenshots' => $screenshots,
                'awards' => $awards
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
}
