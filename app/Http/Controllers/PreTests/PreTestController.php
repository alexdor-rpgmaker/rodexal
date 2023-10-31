<?php

namespace App\Http\Controllers\PreTests;

use App\Former\Game;
use App\Former\Session;
use App\Http\Controllers\Controller;
use App\PreTest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreTestController extends Controller
{
    private Session $sessionInstance;

    public function __construct(Session $sessionInstance)
    {
        $this->sessionInstance = $sessionInstance;

        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function index(Request $request)
    {
        $sessions = Session::orderBy('id_session')->get();
        $currentSession = $this->sessionInstance::currentSession();
        $session = $request->query('session_id')
            // TODO: Faire que si session_id n'existe pas, ça retourne autre chose qu'une erreur 500
            ? $sessions->firstWhere('id_session', $request->query('session_id'))
            : $currentSession;

        $sessionWithQcms = in_array($session->id_session, Session::IDS_SESSIONS_WITH_PRE_TESTS);
        abort_unless($sessionWithQcms, Response::HTTP_BAD_REQUEST, "Cette session n'a pas de pré-qualification ou de QCM.");

        $games = Game::where('id_session', $session->id_session);

        // If pre-tests are not finished, we only display pre-tests of disqualified games
        if ($session === $currentSession && !$session->preTestsAreFinished()) {
            $games = $games->where('statut_jeu', 'disqualified');
        }

        $games = $games->orderBy('nom_jeu')->get();
        $preTestsByGameId = PreTestHelper::fetchAndGroupPreTestsByGameId($games);
        $games = $games->filter(fn($game) => $preTestsByGameId->has($game->id_jeu));

        return view('pre_tests.index', [
            'games' => $games,
            'session' => $session,
            'currentSession' => $currentSession,
            'preTestsByGameId' => $preTestsByGameId
        ]);
    }
}
