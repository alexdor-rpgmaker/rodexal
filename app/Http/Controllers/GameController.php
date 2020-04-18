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

        return view('games.index', [
            'sessionId' => $session ? $session->id_session : null,
            'sessionName' => $session ? $session->nom_session : null
        ]);
    }
}
