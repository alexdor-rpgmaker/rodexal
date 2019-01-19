<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function index()
    {
        return view('words.index', ['words' => Word::all()]);
    }
}
