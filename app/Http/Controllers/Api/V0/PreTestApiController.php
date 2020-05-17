<?php

namespace App\Http\Controllers\Api\V0;

use App\PreTest;
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
        $preTests = PreTest::select(
            'id',
            'user_id',
            'game_id',
            'questionnaire',
            'final_thought',
            'created_at',
            'updated_at'
        );

        // TODO : Fetch from database
        // Begin dirty way to choose session
        $minGameId = null;
        $maxGameId = null;
        $sessionId = $request->session_id ? (int) $request->session_id : 20;
        if ($sessionId == 20) {
            $minGameId = 975;
            $maxGameId = 1007;
        } else if ($sessionId == 19) {
            $maxGameId = 974;
        }
        if ($minGameId) {
            $preTests = $preTests->where('game_id', '>=', $minGameId);
        }
        if ($maxGameId) {
            $preTests = $preTests->where('game_id', '<=', $maxGameId);
        }
        // End dirty way to choose session

        $preTests = $preTests->get();

        $fields = Arr::pluck(PreTest::FIELDS, 'id');
        $preTests->map(function ($preTest) use ($fields) {
            foreach ($fields as $field) {
                $preTest[Str::snake($field)] = Arr::get($preTest, "questionnaire.$field.activated");
            }
            Arr::forget($preTest, 'questionnaire');
            return $preTest;
        });

        return response()->json($preTests, 200);
    }
}
