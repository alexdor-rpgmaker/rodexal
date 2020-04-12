<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\V0;

use App\Former\Game;
use App\Former\Session;
use App\Helpers\StringParser;
use App\Http\Controllers\Controller;

use Exception;
use DateTime;
use DateTimeZone;

class GameApiController extends Controller
{
    public function index()
    {
        $games = Game::with(['session', 'contributors.member'])->paginate(30);
        $games->getCollection()->transform(function ($game) {
            $authors = $game->contributors->transform(function ($contributor) {
                return [
                    'id' => $contributor->id_membre,
                    'username' => $contributor->id_membre ? StringParser::html($contributor->member->pseudo) : $contributor->nom_membre,
                    'role' => $contributor->role
                ];
            });

            return [
                'id' => $game->id_jeu,
                'status' => $game->getStatus(), // TODO N+1
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
                'created_at' => $this->dateAsIso($game->date_inscription),
                'description' => $game->description_jeu,
                'download_links' => $this->extractDownloadLinks($game),
                'authors' => $authors
            ];
        });
        return response()->json($games, 200);
    }

    private function dateAsIso($date)
    {
        // TODO : Use Carbon
        $parisTimezone = new DateTimeZone('Europe/Paris');
        try {
            $dateWithTimezone = new DateTime($date, $parisTimezone);
            return $dateWithTimezone->format(DateTime::ISO8601);
        } catch (Exception $e) {
            return null;
        }
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
