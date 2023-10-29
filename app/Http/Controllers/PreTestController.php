<?php

namespace App\Http\Controllers;

use App\Former\Game;
use App\Former\Juror;
use App\Former\Session;
use App\Former\TestSuite;
use App\Former\TestSuiteAssignedJuror;
use App\Helpers\StringParser;
use App\PreTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            // Si session_id n'existe pas, retourne une erreur 500
            ? $sessions->firstWhere('id_session', $request->query('session_id'))
            : $currentSession;

        $sessionWithQcms = in_array($session->id_session, Session::IDS_SESSIONS_WITH_QCM);
        abort_unless($sessionWithQcms, Response::HTTP_BAD_REQUEST, "Cette session n'a pas de QCM.");

        $games = Game::where('id_session', $session->id_session);

        // If pre-tests are not finished, we only display pre-tests of disqualified games
        if ($session === $currentSession && !$session->preTestsAreFinished()) {
            $games = $games->where('statut_jeu', 'disqualified');
        }

        $games = $games->orderBy('nom_jeu')->get();
        $preTestsByGameId = $this->fetchAndGroupPreTestsByGameId($games);
        $games = $games->filter(fn($game) => $preTestsByGameId->has($game->id_jeu));

        return view('pre_tests.index', [
            'games' => $games,
            'session' => $session,
            'currentSession' => $currentSession,
            'preTestsByGameId' => $preTestsByGameId
        ]);
    }

    public function show(PreTest $preTest)
    {
        abort_if($preTest->type != 'qcm', Response::HTTP_NOT_FOUND);

        $game = self::fetchGame($preTest->game_id);

        $preTest->final_thought_explanation = StringParser::richText($preTest->final_thought_explanation);

        return view('pre_tests.show', [
            'pre_test' => $preTest,
            'game' => $game,
        ]);
    }

    public function create(Request $request)
    {
        $gameId = $request->query('game_id');
        self::checkGameIsInUserAssignments($this->sessionInstance, $gameId, Auth::id());
        $alreadyFilledPreTest = PreTest::where('game_id', $gameId)->where('user_id', Auth::id())->first();
        abort_if($alreadyFilledPreTest, Response::HTTP_BAD_REQUEST, "Un QCM a déjà été rempli pour ce jeu");

        $game = self::fetchGame($gameId);
        return view('pre_tests.form', [
            'pre_test' => null,
            'game_id' => $game->id_jeu,
            'game_title' => $game->nom_jeu,
            'form_method' => 'POST',
            'form_url' => route('qcm.store'),
        ]);
    }

    public function store(Request $request)
    {
        self::checkGameIsInUserAssignments($this->sessionInstance, $request->gameId, Auth::id());

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
        $preTest->type = "qcm";
        $preTest->save();

        if ($preTest->final_thought == "ok") {
            self::assignTestToUser($this->sessionInstance, $preTest->game_id, Auth::id());
        }

        return response()->json($preTest, Response::HTTP_OK);
    }

    public function edit(PreTest $preTest)
    {
        abort_if($preTest->type != 'qcm', Response::HTTP_NOT_FOUND);

        $game = self::fetchGame($preTest->game_id);

        $preTest->finalThought = $preTest->final_thought;
        $preTest->finalThoughtExplanation = $preTest->final_thought_explanation;
        return view('pre_tests.form', [
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

    // Helper

    /**
     * @param $games
     * @return Collection
     */
    private function fetchAndGroupPreTestsByGameId($games): Collection
    {
        $gamesId = Arr::pluck($games, 'id_jeu');
        return PreTest::select('pre_tests.*', 'users.name')
            ->join('users', 'users.id', '=', 'pre_tests.user_id')
            ->whereIn('game_id', $gamesId)
            ->orderBy('users.name')
            ->get()
            ->groupBy('game_id');
    }

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
