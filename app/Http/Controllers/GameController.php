<?php

namespace App\Http\Controllers;

use App\Former\Game;
use App\Former\Session;
use App\UseCases\FetchGamesWithParameters;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $sessions = Session::orderBy('id_session')->get();

        $selectedSession = null;
        if ($request->query('session_id')) {
            $selectedSession = $sessions->firstWhere('id_session', $request->query('session_id'));
        }
        $currentSession = $sessions->last();

        $games = FetchGamesWithParameters::perform($request);

        $PER_PAGE_DEFAULT = 50;
        $perPage = isset($request->per_page) && $this->between1And50($request->per_page) ? (int)$request->per_page : $PER_PAGE_DEFAULT;

        $games = $games->orderBy('awarded_categories_count', 'desc')
            ->orderBy('nominated_categories_count', 'desc')
            ->orderBy('id_jeu')
            ->paginate($perPage);

        // TODO : Group 1-game software in "other" category
        $softwares = Game::select("support")->distinct()->where('support', '!=', '')->orderBy('support')->pluck("support");

        $request->flash();
        return view('games.index', [
            'games' => $games,
            'sessions' => $sessions,
            'softwares' => $softwares,
            'currentSession' => $currentSession,
            'selectedSession' => $selectedSession
        ]);
    }

    public function vue(Request $request)
    {
        $session = null;
        if ($request->query('session_id')) {
            $session = Session::find($request->query('session_id'));
        }
        $currentSession = Session::orderBy('id_session', 'desc')->first();

        return view('games.vue', [
            'selectedSession' => $session,
            'currentSession' => $currentSession
        ]);
    }

    private function between1And50($number)
    {
        return in_array(intval($number), range(1, 50));
    }
}
