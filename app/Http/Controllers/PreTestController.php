<?php

namespace App\Http\Controllers;

use App\PreTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreTestController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function index()
    {
        $preTests = PreTest::all();

		return view('pre-tests.index', [
            'pre_tests' => $preTests
        ]);
    }

    public function show(PreTest $preTest)
    {
		return view('pre-tests.show', [
            'pre_test' => $preTest
        ]);
    }

    public function create()
    {
		return view('pre-tests.create', [
            'pre_test' => new PreTest,
            'title' => 'Ajouter un mot au dictionnaire',
            'form_method' => 'POST',
            'form_url' => route('dictionnaire.store')
        ]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'gameId' => 'required',
            'finalThought' => 'required|boolean',
            'finalThoughtExplanation' => 'nullable|string',
            'questionnaire.blockingBug.activated' => 'required|boolean',
            'questionnaire.blockingBug.explanation' => 'nullable|string',
            'questionnaire.notAutonomous.activated' => 'required|boolean',
            'questionnaire.notAutonomous.explanation' => 'nullable|string',
            'questionnaire.notLaunchable.activated' => 'required|boolean',
            'questionnaire.notLaunchable.explanation' => 'nullable|string',
            'questionnaire.severalBugs.activated' => 'required|boolean',
            'questionnaire.severalBugs.explanation' => 'nullable|string',
            'questionnaire.spellingMistakes.activated' => 'required|boolean',
            'questionnaire.spellingMistakes.explanation' => 'nullable|string',
            'questionnaire.tooHard.activated' => 'required|boolean',
            'questionnaire.tooHard.explanation' => 'nullable|string',
            'questionnaire.tooShort.activated' => 'required|boolean',
            'questionnaire.tooShort.explanation' => 'nullable|string',
            'questionnaire.unplayableAlone.activated' => 'required|boolean',
            'questionnaire.unplayableAlone.explanation' => 'nullable|string'
        ]);

		$preTest = new PreTest;
		$preTest->user_id = Auth::id();
		$preTest->game_id = $request->gameId;
		$preTest->final_thought = $request->finalThought;
		$preTest->final_thought_explanation = $request->finalThoughtExplanation;
		$preTest->questionnaire = $request->questionnaire;
        $preTest->save();

        return response()->json($preTest, 200);
    }
}
