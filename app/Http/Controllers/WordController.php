<?php

namespace App\Http\Controllers;

use Log;
use Transliterator;
use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WordController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Word::class, 'word', [
            'except' => ['edit', 'update']
        ]); // Except is temporary (no idea why this is not working)
    }

	public function index(Request $request)
	{
        $letters = Word::selectRaw('UPPER(LEFT(label, 1)) AS letter')
            ->groupBy('letter')
            ->orderBy('letter')
            ->pluck('letter')
            ->map(function ($letter, $key) {
                $transliterator = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC');
                return $transliterator->transliterate($letter);
            });

        $page_letter = strtoupper($request->query('lettre'));
        $page_letter = in_array($page_letter, $letters->toArray()) ? $page_letter : null;

        $words = $page_letter 
            ? Word::whereRaw('UPPER(LEFT(label, 1)) = ?', $page_letter)->orderBy('label')->get()
            : Word::orderBy('label')->get();

		return view('words.index', [
            'page_letter' => $page_letter,
            'letters' => $letters,
            'words' => $words
        ]);
    }
    
    public function create()
	{
		return view('words.form', [
            'word' => null,
            'title' => 'Ajouter un mot au dictionnaire',
            'form_method' => 'POST',
            'form_url' => route('dictionnaire.store')
        ]);
    }

    public function store(Request $request)
    {
		$validator = $request->validate([
			'label' => 'required|max:255|regex:/^[0-9A-Z]/',
			'description' => 'required'
		]);
		$word = new Word;
		$word->user_id = Auth::id();
		$word->label = $request->label;
		$word->description = $request->description;
        $word->save();
		return redirect('/dictionnaire');
    }

    public function edit(Word $word)
    {
        $this->authorize('update', $word);  // Except is temporary (no idea why this is not working)
		return view('words.form', [
            'word' => $word,
            'title' => 'Modifier un mot du dictionnaire',
            'form_method' => 'PUT',
            'form_url' => route('dictionnaire.update', $word)
        ]);
    }

    public function update(Request $request, Word $word)
    {
        $this->authorize('update', $word);  // Except is temporary (no idea why this is not working)
        // Log::emergency('controller->update');
		$validator = $request->validate([
			'label' => 'required|max:255|regex:/^[0-9A-Z]/',
			'description' => 'required'
		]);
		$word->label = $request->label;
		$word->description = $request->description;
		$word->save();
		return redirect('/dictionnaire');
    }

    // public function destroy(Word $word)
    // {
    //     //
    // }
}
