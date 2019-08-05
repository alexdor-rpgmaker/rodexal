<?php

namespace App\Http\Controllers;

use Transliterator;
use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Genert\BBCode\BBCode;

class WordController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Word::class, 'word');
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

        $bbCode = new BBCode();
        $words->map(function ($word) use ($bbCode) {
            $descriptionWithEntites = e($word->description);
            $descriptionWithBbCode = $bbCode->convertToHtml($descriptionWithEntites);
            $word->description = nl2br($descriptionWithBbCode);
        });

        return view('words.index', [
            'page_letter' => $page_letter,
            'letters' => $letters,
            'words' => $words
        ]);
    }

    public function create()
    {
        return view('words.form', [
            'word' => new Word,
            'title' => 'Ajouter un mot au dictionnaire',
            'form_method' => 'POST',
            'form_url' => route('dictionnaire.store')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|max:255|regex:/^[0-9A-Z]/',
            'description' => 'required'
        ]);
        $word = new Word;
        $word->user_id = Auth::id();
        $word->label = $request->label;
        $word->description = $request->description;
        $word->save();
        return redirect('/dictionnaire')->with('status', 'Mot bien ajouté au dictionnaire !');
    }

    public function edit(Word $word)
    {
        return view('words.form', [
            'word' => $word,
            'title' => 'Modifier un mot du dictionnaire',
            'form_method' => 'PUT',
            'form_url' => route('dictionnaire.update', $word)
        ]);
    }

    public function update(Request $request, Word $word)
    {
        $request->validate([
            'label' => 'required|max:255|regex:/^[0-9A-Z]/',
            'description' => 'required'
        ]);
        $word->label = $request->label;
        $word->description = $request->description;
        $word->save();
        return redirect('/dictionnaire')->with('status', 'Mot du dictionnaire bien modifié !');
    }

    public function destroy(Word $word)
    {
        $word->delete();
        return redirect('/dictionnaire')->with('status', 'Mot bien supprimé...');
    }
}
