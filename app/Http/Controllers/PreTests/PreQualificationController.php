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

class PreQualificationController extends Controller
{
    private Session $sessionInstance;

    public function __construct(Session $sessionInstance)
    {
        $this->sessionInstance = $sessionInstance;

        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function show(PreTest $preTest)
    {
        abort_if($preTest->type != 'pre-qualification', Response::HTTP_NOT_FOUND);

        $game = PreTestHelper::fetchGame($preTest->game_id);

        $preTest->final_thought_explanation = StringParser::richText($preTest->final_thought_explanation);

        return view('pre_qualifications.show', [
            'pre_test' => $preTest,
            'game' => $game,
        ]);
    }

    public function create(Request $request)
    {
        $gameId = $request->query('game_id');
        PreTestHelper::checkGameIsInUserAssignments($this->sessionInstance, $gameId, Auth::id());
        $alreadyFilledPreQualification = PreTest::where('game_id', $gameId)->where('user_id', Auth::id())->first();
        abort_if($alreadyFilledPreQualification, Response::HTTP_BAD_REQUEST, "Une pré-qualification a déjà été remplie pour ce jeu");

        $game = PreTestHelper::fetchGame($gameId);
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
        PreTestHelper::checkGameIsInUserAssignments($this->sessionInstance, $request->gameId, Auth::id());

        $validator_array = [
            'gameId' => 'required',
            'finalThought' => 'required|in:ok,not-ok,some-problems',
            'finalThoughtExplanation' => 'nullable|string',
        ];
        $disqualifyingFields = Arr::pluck(PreTest::PRE_QUALIFICATIONS_DISQUALIFYING_FIELDS, 'id');
        foreach ($disqualifyingFields as $field) {
            $validator_array["questionnaire.$field.activated"] = 'required|boolean';
            $validator_array["questionnaire.$field.explanation"] = 'nullable|string';
        }
        $notDisqualifyingFields = Arr::pluck(PreTest::PRE_QUALIFICATIONS_NOT_DISQUALIFYING_FIELDS, 'id');
        foreach ($notDisqualifyingFields as $field) {
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
        $preTest->type = "pre-qualification";
        $preTest->save();

        if ($preTest->final_thought != "not-ok") {
            PreTestHelper::assignTestToUser($this->sessionInstance, $preTest->game_id, Auth::id());
        }

        return response()->json($preTest, Response::HTTP_OK);
    }

    public function edit(PreTest $preTest)
    {
        abort_if($preTest->type != 'pre-qualification', Response::HTTP_NOT_FOUND);

        $game = PreTestHelper::fetchGame($preTest->game_id);

        $preTest->finalThought = $preTest->final_thought;
        $preTest->finalThoughtExplanation = $preTest->final_thought_explanation;
        return view('pre_qualifications.form', [
            'pre_test' => $preTest,
            'game_id' => $game->id_jeu,
            'game_title' => $game->nom_jeu,
            'form_method' => 'PUT',
            'form_url' => route('pre_qualifications.update', $preTest->id),
        ]);
    }

    public function update(Request $request, PreTest $preTest): JsonResponse
    {
        $validator_array = [
            'finalThoughtExplanation' => 'nullable|string',
        ];
        $disqualifyingFields = Arr::pluck(PreTest::PRE_QUALIFICATIONS_DISQUALIFYING_FIELDS, 'id');
        foreach ($disqualifyingFields as $field) {
            $validator_array["questionnaire.$field.activated"] = 'required|boolean';
            $validator_array["questionnaire.$field.explanation"] = 'nullable|string';
        }
        $notDisqualifyingFields = Arr::pluck(PreTest::PRE_QUALIFICATIONS_NOT_DISQUALIFYING_FIELDS, 'id');
        foreach ($notDisqualifyingFields as $field) {
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
