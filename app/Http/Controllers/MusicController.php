<?php

namespace App\Http\Controllers;

// use BBCode;

class MusicController extends Controller
{
    public function index()
    {
        return view('musics.index');
    }
}
