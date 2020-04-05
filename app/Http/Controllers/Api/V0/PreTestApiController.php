<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\PreTest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PreTestApiController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PreTest::class, 'pre_test');
    }

    public function index()
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

        $fields = Arr::pluck(PreTest::FIELDS, 'id');
        $preTests = array_map(function ($preTest) use ($fields) {
            foreach ($fields as $field) {
                $preTest[Str::snake($field)] = Arr::get($preTest, "questionnaire.$field.activated");
            }
            Arr::forget($preTest, 'questionnaire');
            return $preTest;
        }, $preTests);

        return response()->json($preTests, 200);
    }
}
