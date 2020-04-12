<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\V0;

use App\Former\Game;
use App\Http\Controllers\Controller;

use Exception;
use DateTime;
use DateTimeZone;

class GameApiController extends Controller
{
    public function index()
    {
        $games = Game::paginate(30);
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
                'logo' => $game->logoUrl(),
                'created_at' => $this->dateAsIso($game->date_inscription),
                'description' => $game->description_jeu,
            ];
        });
        return response()->json($games, 200);
    }

    private function dateAsIso($date)
    {
        $parisTimezone = new DateTimeZone('Europe/Paris');
        try {
            $dateWithTimezone = new DateTime($date, $parisTimezone);
            return $dateWithTimezone->format(DateTime::ISO8601);
        } catch (Exception $e) {
            return null;
        }
    }
}
