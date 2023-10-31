<?php

namespace App\Http\Controllers\PreTests;

use App\Former\Session;
use App\Helpers\StringParser;
use App\Http\Controllers\Controller;
use App\PreTest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class QcmController extends Controller
{
    private Session $sessionInstance;

    public function __construct(Session $sessionInstance)
    {
        $this->sessionInstance = $sessionInstance;

        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function show(PreTest $preTest)
    {
        abort_if($preTest->type != 'qcm', Response::HTTP_NOT_FOUND);

        $game = PreTestHelper::fetchGame($preTest->game_id);

        $preTest->final_thought_explanation = StringParser::richText($preTest->final_thought_explanation);

        return view('qcm.show', [
            'pre_test' => $preTest,
            'game' => $game,
        ]);
    }

    public function create(Request $request)
    {
        $gameId = $request->query('game_id');
        PreTestHelper::checkGameIsInUserAssignments($this->sessionInstance, $gameId, Auth::id());
        $alreadyFilledPreTest = PreTest::where('game_id', $gameId)->where('user_id', Auth::id())->first();
        abort_if($alreadyFilledPreTest, Response::HTTP_BAD_REQUEST, "Un QCM a déjà été rempli pour ce jeu");

        $game = PreTestHelper::fetchGame($gameId);
        return view('qcm.form', [
            'pre_test' => null,
            'game_id' => $game->id_jeu,
            'game_title' => $game->nom_jeu,
            'form_method' => 'POST',
            'form_url' => route('qcm.store'),
        ]);
    }

    public function store(Request $request)
    {
        PreTestHelper::checkGameIsInUserAssignments($this->sessionInstance, $request->gameId, Auth::id());

        $validator_array = [
            'gameId' => 'required',
            'finalThought' => 'required|in:ok,not-ok',
            'finalThoughtExplanation' => 'nullable|string',
        ];
        $fields = Arr::pluck(PreTest::QCM_FIELDS, 'id');
        foreach ($fields as $field) {
            $validator_array["questionnaire.$field.activated"] = 'required|boolean';
            $validator_array["questionnaire.$field.explanation"] = 'nullable|string';
        }

        $this->validate($request, $validator_array);

        $preTest = new PreTest;
        $preTest->user_id = Auth::id();
        $preTest->game_id = $request->gameId;
        $preTest->final_thought = $request->finalThought;
        $preTest->final_thought_explanation = $request->finalThoughtExplanation;
        $preTest->questionnaire = $request->questionnaire;
        $preTest->type = "qcm";
        $preTest->save();

        if ($preTest->final_thought != "not-ok") {
            PreTestHelper::assignTestToUser($this->sessionInstance, $preTest->game_id, Auth::id());
        }

        return response()->json($preTest, Response::HTTP_OK);
    }

    public function edit(PreTest $preTest)
    {
        abort_if($preTest->type != 'qcm', Response::HTTP_NOT_FOUND);

        $game = PreTestHelper::fetchGame($preTest->game_id);

        $preTest->finalThought = $preTest->final_thought;
        $preTest->finalThoughtExplanation = $preTest->final_thought_explanation;
        return view('qcm.form', [
            'pre_test' => $preTest,
            'game_id' => $game->id_jeu,
            'game_title' => $game->nom_jeu,
            'form_method' => 'PUT',
            'form_url' => route('qcm.update', $preTest->id),
        ]);
    }

    public function update(Request $request, PreTest $preTest): JsonResponse
    {
        $validator_array = [
            'finalThoughtExplanation' => 'nullable|string',
        ];
        $fields = Arr::pluck(PreTest::QCM_FIELDS, 'id');
        foreach ($fields as $field) {
            $validator_array["questionnaire.$field.activated"] = 'required|boolean';
            $validator_array["questionnaire.$field.explanation"] = 'nullable|string';
        }
        $this->validate($request, $validator_array);

        $preTest->final_thought_explanation = $request->finalThoughtExplanation;
        $preTest->questionnaire = $request->questionnaire;
        $preTest->save();

        return response()->json($preTest, Response::HTTP_OK);
    }
}
