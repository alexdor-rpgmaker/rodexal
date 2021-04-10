<?php

namespace App\Http\Controllers;

use App\PreTest;
use App\Former\Game;
use App\Former\Session;
use App\Helpers\StringParser;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PreTestController extends Controller
{

    /**
     * @var GuzzleClient
     */
    private $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function index(Request $request)
    {
        $sessions = Session::orderBy('id_session')->get();
        $currentSession = $sessions->last();
        $session = $request->query('session_id')
            ? $sessions->firstWhere('id_session', $request->query('session_id'))
            : $sessions->last();

        $games = Game::where('id_session', $session->id_session);

        // If pre-tests are not finished, we only display pre-tests of disqualified games
        if ($session == $currentSession && !$session->preTestsAreFinished()) {
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
        $game = self::fetchGame($preTest->game_id, $this->client);

        $preTest->final_thought_explanation = StringParser::richText($preTest->final_thought_explanation);

        return view('pre_tests.show', [
            'pre_test' => $preTest,
            'game' => $game,
        ]);
    }

    public function create(Request $request)
    {
        $gameId = $request->query('game_id');
        self::checkGameIsInUserAssignments($gameId, Auth::id(), $this->client);
        $alreadyFilledPreTest = PreTest::where('game_id', $gameId)->where('user_id', Auth::id())->first();
        abort_if($alreadyFilledPreTest, Response::HTTP_BAD_REQUEST, "Un QCM a déjà été rempli pour ce jeu");

        $game = self::fetchGame($gameId, $this->client);
        return view('pre_tests.form', [
            'pre_test' => null,
            'title' => "Remplir un QCM pour le jeu $game->title",
            'game_id' => $game->id,
            'form_method' => 'POST',
            'form_url' => route('qcm.store'),
        ]);
    }

    public function store(Request $request)
    {
        self::checkGameIsInUserAssignments($request->gameId, Auth::id(), $this->client);

        $validator_array = [
            'gameId' => 'required',
            'finalThought' => 'required|boolean',
            'finalThoughtExplanation' => 'nullable|string',
        ];
        $fields = Arr::pluck(PreTest::FIELDS, 'id');
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
        $preTest->save();

        if ($request->finalThought) {
            self::assignTestToUser($request->gameId, Auth::id(), $this->client);
        }

        return response()->json($preTest, Response::HTTP_OK);
    }

    public function edit(PreTest $preTest)
    {
        $game = self::fetchGame($preTest->game_id, $this->client);

        $preTest->finalThought = $preTest->final_thought == 1;
        $preTest->finalThoughtExplanation = $preTest->final_thought_explanation;
        return view('pre_tests.form', [
            'pre_test' => $preTest,
            'title' => "Modifier le QCM du jeu $game->title",
            'game_id' => $game->id,
            'form_method' => 'PUT',
            'form_url' => route('qcm.update', $preTest->id),
        ]);
    }

    public function update(Request $request, PreTest $preTest): JsonResponse
    {
        $validator_array = [
            'finalThought' => 'required|boolean',
            'finalThoughtExplanation' => 'nullable|string',
        ];
        $fields = Arr::pluck(PreTest::FIELDS, 'id');
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
     * @return mixed
     */
    private function fetchAndGroupPreTestsByGameId($games)
    {
        $gamesId = Arr::pluck($games, 'id_jeu');
        return PreTest::select('pre_tests.*', 'users.name')
            ->join('users', 'users.id', '=', 'pre_tests.user_id')
            ->whereIn('game_id', $gamesId)
            ->orderBy('users.name')
            ->get()
            ->groupBy('game_id');
    }

    // TODO : Fetch directly from database instead of API call
    private static function checkGameIsInUserAssignments($gameId, $userId, GuzzleClient $client): void
    {
        if (env('DUSK', false)) {
            return;
        }
        $assignments = self::fetchUserAssignments($userId, $client);
        $gameInAssignment = current(array_filter($assignments, function ($assignment) use ($gameId) {
            return $assignment->game_id == $gameId;
        }));

        // TODO : Mettre un meilleur message d'erreur si jeu attribué mais d'une session passée
        abort_unless($gameInAssignment, Response::HTTP_FORBIDDEN, "Ce jeu ne vous est pas attribué !");
    }

    // TODO : Fetch directly from database instead of API call
    private static function fetchUserAssignments($userId, GuzzleClient $client)
    {
        try {
            $currentSession = Session::orderBy('id_session', 'desc')->first();
            $response = $client->request('GET', '/api/v0/attributions.php', [
                'base_uri' => env('FORMER_APP_URL'),
                'query' => [
                    'id_membre' => intval($userId),
                    'id_session' => $currentSession->id_session,
                ],
                'verify' => App::environment('local') !== true
            ]);

            return json_decode($response->getBody());
        } catch (RequestException $e) {
            return self::formerAppApiError($e);
        }
    }

    // TODO : Fetch directly from database instead of API call
    private static function fetchGame($id, GuzzleClient $client)
    {
        try {
            $response = $client->request('GET', '/api/v0/jeu.php', [
                'base_uri' => env('FORMER_APP_URL'),
                'query' => ['id' => intval($id)],
                'verify' => App::environment('local') !== true
            ]);
            return json_decode($response->getBody());
        } catch (RequestException $e) {
            return self::formerAppApiError($e);
        }
    }

    // TODO : Insert directly in database instead of API call
    private static function assignTestToUser($game_id, $user_id, GuzzleClient $client): void
    {
        if (env('DUSK', false)) {
            return;
        }
        try {
            $client->request('POST', '/api/v0/attribution.php', [
                'base_uri' => env('FORMER_APP_URL'),
                'json' => [
                    'id_jeu' => $game_id,
                    'id_membre' => $user_id,
                ],
                'headers' => ['X-Api-Key' => env('FORMER_APP_API_KEY')],
                'verify' => App::environment('local') !== true
            ]);
        } catch (RequestException $e) {
            self::formerAppApiError($e);
            return;
        }
    }

    /**
     * @param RequestException $e
     * @return null
     */
    private static function formerAppApiError(RequestException $e)
    {
        Log::error($e);
        abort(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        return null;
    }
}
