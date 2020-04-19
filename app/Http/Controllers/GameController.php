<?php

namespace App\Http\Controllers;

use App\Former\Session;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $session = null;
        if ($request->query('session_id')) {
            $session = Session::find($request->query('session_id'));
        }
        $currentSession = Session::orderBy('id_session', 'desc')->first();

        return view('games.index', [
            'selectedSession' => $session,
            'currentSession' => $currentSession
        ]);
    }
}
