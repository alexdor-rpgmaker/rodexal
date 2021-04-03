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

        $games = $games->orderByDesc('awarded_categories_count')
            ->orderByDesc('nominated_categories_count')
            ->orderBy('id_jeu')
            ->paginate(Pagination::perPage($request->per_page));

        $games->getCollection()->transform(fn($game) => CleanGameAttributes::perform($game, true));

        // TODO : Remove software category with only one game (move the game in "other" category)
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
        $currentSession = Session::orderByDesc('id_session')->first();

        return view('games.vue', [
            'selectedSession' => $session,
            'currentSession' => $currentSession
        ]);
    }
}
