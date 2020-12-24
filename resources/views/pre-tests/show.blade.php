@extends('layouts.app')

@section('title', 'Pré-test')

@push('stylesheets')
    <link href="{{ asset('css/pre_tests.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="qcm" class="col-md-8">
                <div id="titre_corps">QCM : {{ $game->title }}</div>
                <div id="sous_titre_corps">
                    Par
                    <a href="{{ env('FORMER_APP_URL') }}?p=profil&membre={{ $pre_test->user_id }}">
                        {{ $pre_test->user->name }}
                    </a>
                </div>
                <div class="barre_boutons">
                    <span class="bordure_boutons">
                        {{-- TODO : Change for $game->getUrl() when $game is instance of Game class --}}
                        <a class="bouton voir-jeu" href="{{ env('FORMER_APP_URL') }}?p=jeu&id={{ $game->id }}">
                            Voir la fiche du jeu
                        </a>
                        <a class="bouton modifier-pre-test"
                           href="{{ route('qcm.edit', ['pre_test' => $pre_test->id]) }}">
                            Modifier
                        </a>
                    </span>
                </div>
                <div class="card">
                    <div class="card-body">
                        @foreach (App\PreTest::FIELDS as $field)
                            @if ($pre_test->questionnaire[$field['id']]['activated'])
                                <div class="questionnaire-group">
                                    <p><strong>{{ $field['label'] }}</strong></p>
                                    <div class="explanation">
                                        {{ $pre_test->questionnaire[$field['id']]['explanation'] }}
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if($pre_test->questionnaireHasActivatedFields())
                            <hr/>
                        @endunless

                        <div id="final-thought">
                            <p>
                                Verdict :
                                <span class="{{ $pre_test->final_thought ? 'ok' : 'not-ok' }}">
                                    {{ $pre_test->final_thought ? 'Conforme' : 'Non conforme' }}
                                </span>
                            </p>
                            @if ($pre_test->final_thought_explanation)
                                <div class="explanation">
                                    {!! $pre_test->final_thought_explanation !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
