<?php

namespace App\Http\Controllers;

use App\Former\Game;
use App\Former\Juror;
use App\Former\Session;
use App\Former\TestSuite;
use App\Former\TestSuiteAssignedJuror;
use App\Helpers\StringParser;
use App\PreTest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $game = self::fetchGame($preTest->game_id);

        $preTest->final_thought_explanation = StringParser::richText($preTest->final_thought_explanation);

        return view('pre_qualifications.show', [
            'pre_test' => $preTest,
            'game' => $game,
        ]);
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

        // TODO ?
        // try {
        //     $this->validate($request, $validator_array);
        // } catch (ValidationException $e) {
        //     abort(400, "Non valide");
        // }

        $preTest = new PreTest;
        $preTest->user_id = Auth::id();
        $preTest->game_id = $request->gameId;
        $preTest->final_thought = $request->finalThought;
        $preTest->final_thought_explanation = $request->finalThoughtExplanation;
        $preTest->questionnaire = $request->questionnaire;
        $preTest->type = "pre-qualification";
        $preTest->save();

        if ($preTest->final_thought == "ok") {
            self::assignTestToUser($this->sessionInstance, $preTest->game_id, Auth::id());
        }

        return response()->json($preTest, Response::HTTP_OK);
    }

    public function edit(PreTest $preQualification)
    {
        abort_if($preQualification->type != 'pre-qualification', Response::HTTP_NOT_FOUND);
    }

    // Helper
    // TODO: Factorize with PreTestController

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

    private static function assignTestToUser($session, $game_id, $user_id): void
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
