@extends('layouts.app')

@section('title', 'Dictionnaire')

@push('stylesheets')
    <link href="{{ asset('css/dictionary.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="dictionary" class="col-md-8">
                <div id="titre_corps">Dictionnaire</div>

                @can('create', App\Word::class)
                    <p>
                        <a href="{{ route('dictionnaire.create') }}" class="bouton">
                            <i class="fa fa-plus"></i> Ajouter un mot au dictionnaire
                        </a>
                    </p>
                @endcan

                @if($words->isNotEmpty())
                    <nav class="letters" aria-label="Navigation du dictionnaire">
                        <ul class="pagination-custom">
                            @if(!isset($page_letter))
                                <li class="page-item active">
                                    <span>
                                        Toutes les lettres
                                        <span class="visually-hidden">(actuelle)</span>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link bouton" href="{{ route('dictionnaire.index') }}?">Toutes les lettres</a>
                                </li>
                            @endif

                            @foreach ($letters as $letter)
                                @php
                                    $transliterator = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC');
                                    /** @noinspection PhpUndefinedVariableInspection */
                                    $transliterated_letter = $transliterator->transliterate($letter);
                                    $uppercased_letter = strtoupper($transliterated_letter)
                                @endphp
                                @if($uppercased_letter == $page_letter)
                                    <li class="page-item active">
                                        <span>
                                            {{ $uppercased_letter }}
                                            <span class="visually-hidden">(actuelle)</span>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link bouton"
                                           href="{{ route('dictionnaire.index', ['lettre' => $uppercased_letter]) }}">
                                            {{ $uppercased_letter }}
                                        </a>
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

                                    <div class="card-options float-end">
                                        @can('delete', $word)
                                            <form action="{{ route('dictionnaire.destroy', $word) }}" method="POST"
                                                  style="display: inline;">
                                                @method('DELETE')
                                                @csrf
                                                <button class="delete">
                                                    Supprimer
                                                </button>
                                            </form> -
                                        @endcan
                                        @can('update', $word)
                                            <a href="{{ route('dictionnaire.edit', $word) }}" class="edit">
                                                Modifier
                                            </a> -
                                        @endcan
                                        <a href="{{ route('dictionnaire.index') }}#{{ $word->slug }}"
                                           class="quick-link">
                                            Lien
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    {!! $word->description !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endempty
            </div>
        </div>
    </div>
@stop
