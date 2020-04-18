<?php

namespace App\Http\Controllers;

use App\Former\Session;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = (Session::sessionIdExists($request->query('session_id'))) ? $request->query('session_id') : null;
        return view('games.index', [
            'sessionId' => $sessionId,
        ]);
    }
}
