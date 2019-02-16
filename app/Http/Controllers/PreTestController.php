<?php

namespace App\Http\Controllers;

use App\PreTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class PreTestController extends Controller
{
    private $client;
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function indexApi()
    {
        $preTests = PreTest::select(
            'id',
            'user_id',
            'game_id',
            'questionnaire',
            'final_thought',
            'created_at',
            'updated_at'
        )->get()->toArray();

        $fields = array_pluck(PreTest::FIELDS, 'id');
		$preTests = array_map(function ($preTest) use ($fields) {
            foreach($fields as $field) {
                $preTest[snake_case($field)] = array_get($preTest, "questionnaire.$field.activated");
            }
            array_forget($preTest, 'questionnaire');
            return $preTest;
        }, $preTests);

        return response()->json($preTests, 200);
    }

    public function show(PreTest $preTest)
    {
        $game = self::fetchGame($preTest->game_id, $this->client);
		return view('pre-tests.show', [
            'pre_test' => $preTest,
            'game' => $game
        ]);
    }

    public function create(Request $request)
    {
        $gameId = $request->query('game_id');
        self::checkGameIsInUserAssignments($gameId, Auth::id(), $this->client);

        $game = self::fetchGame($gameId, $this->client);
		return view('pre-tests.form', [
            'pre_test' => new PreTest,
            'title' => "Remplir un QCM pour le jeu $game->title",
            'game_id' => $game->id,
            'form_method' => 'POST',
            'form_url' => route('qcm.store')
        ]);
    }

    public function store(Request $request) {
        self::checkGameIsInUserAssignments($request->gameId, Auth::id(), $this->client);

        $validator_array = [
            'gameId' => 'required',
            'finalThought' => 'required|boolean',
            'finalThoughtExplanation' => 'nullable|string'
        ];
        $fields = array_pluck(PreTest::FIELDS, 'id');
        foreach($fields as $field) {
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
        $preTest->save();

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
            'form_url' => route('qcm.update', $preTest->id)
        ]);
    }

    public function update(Request $request, PreTest $preTest)
    {
        $validator_array = [
            'finalThought' => 'required|boolean',
            'finalThoughtExplanation' => 'nullable|string'
        ];
        $fields = array_pluck(PreTest::FIELDS, 'id');
        foreach($fields as $field) {
            $validator_array["questionnaire.$field.activated"] = 'required|boolean';
            $validator_array["questionnaire.$field.explanation"] = 'nullable|string';
        }
        $this->validate($request, $validator_array);

		$preTest->final_thought = $request->finalThought;
		$preTest->final_thought_explanation = $request->finalThoughtExplanation;
		$preTest->questionnaire = $request->questionnaire;
		$preTest->save();

        return response()->json($preTest, 200);
    }

    // Helper

    private static function checkGameIsInUserAssignments($gameId, $userId, $client)
    {
        if (env('DUSK', false)) {
            return true;
        }
        $assignments = self::fetchUserAssignments($userId, $client);
        $gameInAssignment = current(array_filter($assignments, function($assignment) use($gameId) {
            return $assignment->game_id == $gameId;
        }));
        abort_unless($gameInAssignment, 403, "Ce jeu ne vous est pas attribué !");
    }

    private static function fetchUserAssignments($userId, $client)
    {
        try {
            $response = $client->request('GET', '/api/v0/attributions.php', [
                'base_uri' => env('FORMER_APP_URL'),
                'query' => [
                    'id_membre' => intval($userId),
                    'id_session' => 19 // TODO: Make this variable
                ]
            ]);
        } catch (RequestException $e) {
            abort($e->getResponse()->getStatusCode());
        }
        return json_decode($response->getBody());
    }

    private static function fetchGame($id, $client)
    {
        try {
            $response = $client->request('GET', '/api/v0/jeu.php', [
                'base_uri' => env('FORMER_APP_URL'),
                'query' => ['id' => intval($id)]
            ]);
        } catch (RequestException $e) {
            abort($e->getResponse()->getStatusCode());
        }
        return json_decode($response->getBody());
    }
}
