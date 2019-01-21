<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WordController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Word::class, 'word');
    }

	public function index(Request $request)
	{
        // return redirect()->route('home')->with('status', 'You are now logged in.');
        $letters = DB::table('words')->selectRaw('LEFT(label, 1) AS letter')->groupBy('letter')->orderBy('letter')->pluck('letter');

        $letter = $request->query('lettre');
        $words = $letter 
            ? DB::table('words')->whereRaw('LEFT(label, 1) = ?', $letter)->orderBy('label')->get() 
            : Word::orderBy('label')->get();
		return view('words.index', [
            'requestquery' => $request,
            'page_letter' => $letter,
            'letters' => $letters,
            'words' => $words
        ]);
    }
    
    public function create()
	{
		return view('words.create');
    }

    public function store(Request $request)
    {
		$validator = $request->validate([
			'label' => 'required|max:255',
			'description' => 'required'
		]);
		// if ($validator->fails()) {
		// 	return redirect('/')
		// 		->withInput()
		// 		->withErrors($validator);
		// }
		$word = new Word;
		$word->user_id = Auth::id();
		$word->label = $request->label;
		$word->description = $request->description;
		$word->save();
		return redirect('/');
    }

    // public function show(Word $word)
    // {
    //     return view('words.show', ['word' => Word::findOrFail($id)]);
    // }

    // public function edit(Word $word)
    // {
    //     //
    // }

    // public function update(Request $request, Word $word)
    // {
    //     //
    // }

    // public function destroy(Word $word)
    // {
    //     //
    // }
}
