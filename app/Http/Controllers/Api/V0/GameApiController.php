<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;

class GameApiController extends Controller
{
    public function index()
    {
        $games = \App\Former\Game::paginate(30);
        return response()->json($games, 200);
    }
}
