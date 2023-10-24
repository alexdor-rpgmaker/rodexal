<?php

namespace App\Http\Controllers;

use App\Former\Game;
use App\Former\Session;
use App\Former\TestSuiteAssignedJuror;
use App\PreTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreQualificationController extends Controller
{
    private Session $sessionInstance;

    public function __construct(Session $sessionInstance)
    {
        $this->sessionInstance = $sessionInstance;

        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function create(Request $request)
    {
        $gameId = $request->query('game_id');
        self::checkGameIsInUserAssignments($this->sessionInstance, $gameId, Auth::id());
        $alreadyFilledPreQualification = PreTest::where('game_id', $gameId)->where('user_id', Auth::id())->first();
        abort_if($alreadyFilledPreQualification, Response::HTTP_BAD_REQUEST, "Une pré-qualification a déjà été remplie pour ce jeu");

        $game = self::fetchGame($gameId);
        return view('pre_qualifications.form', [
            'pre_test' => null,
            'game_id' => $game->id_jeu,
            'game_title' => $game->nom_jeu,
            'form_method' => 'POST',
            'form_url' => route('pre_qualifications.store'),
        ]);
    }

    public function store(Request $request)
    {
        self::checkGameIsInUserAssignments($this->sessionInstance, $request->gameId, Auth::id());
    }

    // Helper

    private static function checkGameIsInUserAssignments($session, $gameId, $userId): void
    {
        if (env('DUSK', false)) {
            return;
        }

        // A voir si les conditions sur les jurés ou jeu (statut > 0...) peuvent être portées par la classe
        $jurorHasToPreTestThisGame = TestSuiteAssignedJuror::with([
            'suite',
            'juror',
            'game',
        ])->where('statut_jeu_jure', '=', 2)
            ->whereRelation('suite', 'is_pre_test', 1)
            ->whereRelation('juror', 'id_membre', $userId)
            ->whereRelation('game', 'id_session', $session::currentSession()->id_session)
            ->whereRelation('game', 'id_jeu', $gameId)
            ->exists();

        // TODO : Mettre un meilleur message d'erreur si jeu attribué mais d'une session passée
        abort_unless($jurorHasToPreTestThisGame, Response::HTTP_FORBIDDEN, "Ce jeu ne vous est pas attribué !");
    }

    private static function fetchGame($id): Game
    {
        return Game::select('id_jeu', 'statut_jeu', 'nom_jeu', 'description_jeu')
            ->firstWhere('id_jeu', '=', $id);
    }
}
