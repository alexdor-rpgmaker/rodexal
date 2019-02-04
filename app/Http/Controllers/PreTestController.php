<?php

namespace App\Http\Controllers;

use App\PreTest;
use Illuminate\Http\Request;

class PreTestController extends Controller
{
    public function create()
    {
		return view('pre-tests.create', [
            'word' => new PreTest,
            'title' => 'Ajouter un mot au dictionnaire',
            'form_method' => 'POST',
            'form_url' => route('dictionnaire.store')
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(PreTest $preTest)
    {
        //
    }
}
