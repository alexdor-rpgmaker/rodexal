<?php

namespace App\Http\Controllers\PreTests;

use App\Former\Game;
use App\Former\Juror;
use App\Former\TestSuite;
use App\Former\TestSuiteAssignedJuror;
use App\PreTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PreTestHelper
{
    /**
     * @param $games
     * @return Collection
     */
    public static function fetchAndGroupPreTestsByGameId($games): Collection
    {
        $gamesId = Arr::pluck($games, 'id_jeu');
        return PreTest::select('pre_tests.*', 'users.name')
            ->join('users', 'users.id', '=', 'pre_tests.user_id')
            ->whereIn('game_id', $gamesId)
            ->orderBy('users.name')
            ->get()
            ->groupBy('game_id');
    }

    public static function checkGameIsInUserAssignments($session, $gameId, $userId): void
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

    public static function fetchGame($id): Game
    {
        return Game::select('id_jeu', 'statut_jeu', 'nom_jeu', 'description_jeu')
            ->firstWhere('id_jeu', '=', $id);
    }

    public static function assignTestToUser($session, $game_id, $user_id): void
    {
        if (env('DUSK', false)) {
            return;
        }

        // Récupérer le statut de jury du user
        $jurorId = Juror::select("id_jury")
            ->where('statut_jury', '=', 1)
            ->where('id_membre', '=', $user_id)
            ->where('id_session', '=', $session::currentSession()->id_session)
            ->pluck("id_jury")
            ->first();

        // Récupérer le statut du jeu
        $game = Game::select('nom_jeu', 'informations')
            ->firstWhere('id_jeu', '=', $game_id);

        if (!$jurorId || !$game) {
            Log::error("Juré ou jeu non trouvé");
            die();
        }

        // Dernière série qui n'est pas de type pré-test
        $lastTestSuite = TestSuite::where('is_pre_test', '=', 0)
            ->orderByDesc('id_serie')
            ->first();
        $testSuiteAssignedJuror = TestSuiteAssignedJuror::where('statut_jeu_jure', '>', 0)
            ->where('id_serie', '=', $lastTestSuite->id_serie)
            ->where('id_jeu', '=', $game_id)
            ->where('id_jury', '=', $jurorId)
            ->first();

        if ($testSuiteAssignedJuror) {
            Log::error("Jeu déjà assigné à ce juré");
            die();
        }

        TestSuiteAssignedJuror::create([
            'id_serie' => $lastTestSuite->id_serie,
            'id_jeu' => $game_id,
            'id_jury' => $jurorId,
            'statut_jeu_jure' => 2,
        ]);
    }
}
