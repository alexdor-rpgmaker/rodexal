<?php

namespace App\Http\Controllers;

class MusicController extends Controller
{
    public function index()
    {
        return view('musics.index');
    }
}
