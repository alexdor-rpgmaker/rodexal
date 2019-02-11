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
		return view('pre-tests.show', [
            'pre_test' => $preTest
        ]);
    }

    public function create(Request $request)
    {
        $game = self::fetchGame($request->query('game_id'), $this->client);
		return view('pre-tests.form', [
            'pre_test' => new PreTest,
            'title' => 'Ajouter un QCM',
            'game' => [
                'id' => $game->id,
                'title' => $game->title
            ],
            'form_method' => 'POST',
            'form_url' => route('qcm.store')
        ]);
    }

    public function store(Request $request) {
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
		return view('pre-tests.form', [
            'pre_test' => $preTest,
            'title' => 'Modifier un QCM',
            'game' => [
                'id' => $game->id,
                'title' => $game->title
            ],
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
