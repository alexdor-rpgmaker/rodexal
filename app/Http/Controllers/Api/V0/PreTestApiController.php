<?php

namespace App\Http\Controllers\Api\V0;

use App\PreTest;
use App\Former\Game;
use App\Former\Session;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

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
            : Arr::last(Session::IDS_SESSIONS_WITH_QCM);
        if (!in_array($sessionId, Session::IDS_SESSIONS_WITH_QCM)) {
            return response()->json([]);
        }

        $games = Game::withoutGlobalScope('deleted')->where('id_session', $sessionId)->get();
        $gamesId = Arr::pluck($games, 'id_jeu');

        $preTests = PreTest::select(
            'id',
            'user_id',
            'game_id',
            'questionnaire',
            'final_thought',
            'created_at',
            'updated_at'
        )->whereIn('game_id', $gamesId)
            ->get();

        $fields = Arr::pluck(PreTest::QCM_FIELDS, 'id');
        $preTests->map(function ($preTest) use ($fields) {
            foreach ($fields as $field) {
                $preTest[Str::snake($field)] = Arr::get($preTest, "questionnaire.$field.activated");
            }
            Arr::forget($preTest, 'questionnaire');
            return $preTest;
        });

        return response()->json($preTests);
    }
}
