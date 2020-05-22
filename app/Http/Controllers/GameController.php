<?php

namespace App\Http\Controllers;

use App\Pagination;
use App\Former\Game;
use App\Former\Session;
use App\UseCases\CleanGameAttributes;
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

        $games = $games->orderBy('awarded_categories_count', 'desc')
            ->orderBy('nominated_categories_count', 'desc')
            ->orderBy('id_jeu')
            ->paginate(Pagination::perPage($request->per_page));

        $games->getCollection()->transform(fn($game) => CleanGameAttributes::perform($game, true));

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
}
