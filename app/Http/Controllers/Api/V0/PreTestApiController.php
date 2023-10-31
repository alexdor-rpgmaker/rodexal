<?php

namespace App\Http\Controllers\Api\V0;

use App\Former\Game;
use App\Former\Session;
use App\Http\Controllers\Controller;
use App\PreTest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PreTestApiController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function index(Request $request)
    {
        $sessionId = $request->session_id
            ? (int)$request->session_id
            : Arr::last(Session::IDS_SESSIONS_WITH_PRE_TESTS);
        if (!in_array($sessionId, Session::IDS_SESSIONS_WITH_PRE_TESTS)) {
            return response()->json([]);
        }

        $games = Game::withoutGlobalScope('deleted')->where('id_session', $sessionId)->get();
        $gamesId = Arr::pluck($games, 'id_jeu');

        $preTests = PreTest::select(
            'id',
            'user_id',
            'game_id',
            'final_thought',
            'created_at',
            'updated_at'
        )->whereIn('game_id', $gamesId)
            ->get();

        return response()->json($preTests);
    }
}
