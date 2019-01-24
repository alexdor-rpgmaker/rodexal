@extends('layouts.app')

@push('stylesheets')
    <link href="{{ asset('css/dictionary.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="dictionary" class="col-md-8">
                <h1>Dictionnaire</h1>

                @can('create', App\Word::class)
                    <p>
                        <a href="{{ route('dictionnaire.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Ajouter un mot au dictionnaire
                        </a>
                    </p>
                @endcan

                @if($words->isNotEmpty())
                    <nav class="letters" aria-label="Navigation du dictionnaire">
                        <ul class="pagination">
                            @if(!isset($page_letter))
                                <li class="page-item active">
                                    <span class="page-link">
                                        Toutes les lettres
                                        <span class="sr-only">(actuelle)</span>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="?">Toutes les lettres</a>
                                </li>
                            @endif

                            @foreach ($letters as $letter)
                                @php
                                    $transliterator = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC'); 
                                    $transliterated_letter = $transliterator->transliterate($letter);
                                    $uppercased_letter = strtoupper($transliterated_letter);
                                @endphp
                                    @if($uppercased_letter == $page_letter)
                                        <li class="page-item active">
                                            <span class="page-link">
                                                {{ $uppercased_letter }}
                                                <span class="sr-only">(actuelle)</span>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="?lettre={{$uppercased_letter}}">{{ $uppercased_letter }}</a>
                                        </li>
                                    @endif
                            @endforeach
                        </ul>
                    </nav>
                @endempty

                @if($words->isEmpty())
                    <p>Pas de mots...</p>
                @else
                    <div class="cards">
                        @foreach ($words as $word)
                            <div class="card" id="{{ $word->slug }}">
                                <div class="card-header">
                                    {{ $word->label }}

                                    <div class="card-options float-right">
                                        @can('delete', $word)
                                            <form action="{{ route('dictionnaire.destroy', $word) }}" method="POST"  style="display: inline;">
                                                @method('DELETE')
                                                @csrf
                                                <button style="border: none; background-color: transparent; color: #aaa; padding: 1px 3px 2px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                                            </form>                                            
                                        @endcan
                                        @can('update', $word)
                                            <a href="{{ route('dictionnaire.edit', $word) }}" class="quick-link"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        <a href="#{{ $word->slug }}" class="quick-link"><i class="fas fa-link"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    {!! nl2br(e($word->description)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endempty
            </div>
        </div>
    </div>
@stop
