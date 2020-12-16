<?php

namespace App\Http\Controllers;

use App\PreTest;
use App\Helpers\StringParser;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function show(PreTest $preTest)
    {
        $game = self::fetchGame($preTest->game_id, $this->client);

        $preTest->final_thought_explanation = StringParser::richText($preTest->final_thought_explanation);

        return view('pre-tests.show', [
            'pre_test' => $preTest,
            'game' => $game,
        ]);
    }

    public function create(Request $request)
    {
        $gameId = $request->query('game_id');
        self::checkGameIsInUserAssignments($gameId, Auth::id(), $this->client);
        $alreadyFilledPreTest = PreTest::where('game_id', $gameId)->where('user_id', Auth::id())->first();
        abort_if($alreadyFilledPreTest, 400, "Un QCM a déjà été remplir pour ce jeu");

        $game = self::fetchGame($gameId, $this->client);
        return view('pre-tests.form', [
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

        return response()->json($preTest, 200);
    }

    public function edit(PreTest $preTest)
    {
        $game = self::fetchGame($preTest->game_id, $this->client);

        $preTest->finalThought = $preTest->final_thought == 1;
        $preTest->finalThoughtExplanation = $preTest->final_thought_explanation;
        return view('pre-tests.form', [
            'pre_test' => $preTest,
            'title' => "Modifier le QCM du jeu $game->title",
            'game_id' => $game->id,
            'form_method' => 'PUT',
            'form_url' => route('qcm.update', $preTest->id),
        ]);
    }

    public function update(Request $request, PreTest $preTest)
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

        return response()->json($preTest, 200);
    }

    // Helper

    private static function checkGameIsInUserAssignments($gameId, $userId, GuzzleClient $client)
    {
        if (env('DUSK', false)) {
            return true;
        }
        $assignments = self::fetchUserAssignments($userId, $client);
        $gameInAssignment = current(array_filter($assignments, function ($assignment) use ($gameId) {
            return $assignment->game_id == $gameId;
        }));
        return abort_unless($gameInAssignment, 403, "Ce jeu ne vous est pas attribué !");
    }

    private static function fetchUserAssignments($userId, GuzzleClient $client)
    {
        try {
            $response = $client->request('GET', '/api/v0/attributions.php', [
                'base_uri' => env('FORMER_APP_URL'),
                'query' => [
                    'id_membre' => intval($userId),
                    'id_session' => 21, // TODO: Make this variable
                ],
            ]);

            return json_decode($response->getBody());
        } catch (RequestException $e) {
            Log::warning($e);
            return abort($e->getResponse()->getStatusCode());
        }
    }

    private static function fetchGame($id, GuzzleClient $client)
    {
        try {
            $response = $client->request('GET', '/api/v0/jeu.php', [
                'base_uri' => env('FORMER_APP_URL'),
                'query' => ['id' => intval($id)],
            ]);
            return json_decode($response->getBody());
        } catch (RequestException $e) {
            Log::warning($e);
            return abort($e->getResponse()->getStatusCode());
        }
    }

    private static function assignTestToUser($game_id, $user_id, GuzzleClient $client)
    {
        if (env('DUSK', false)) {
            return null;
        }
        try {
            $client->request('POST', '/api/v0/attribution.php', [
                'base_uri' => env('FORMER_APP_URL'),
                'json' => [
                    'id_jeu' => $game_id,
                    'id_membre' => $user_id,
                ],
                'headers' => ['X-Api-Key' => env('FORMER_APP_API_KEY')],
            ]);
        } catch (RequestException $e) {
            // On logue et on ignore
            Log::warning($e);
        }
    }
}
