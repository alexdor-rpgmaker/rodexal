@extends('layouts.app')

@section('title')
    Pré-Qualification de {{ $game->nom_jeu }} par {{ $pre_test->user->name }}
@stop

@push('stylesheets')
    <link href="{{ asset('css/pre_qualifications.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="pre-qualification" class="col-md-8">
                <div id="titre_corps">Pré-Qualification : {{ $game->nom_jeu }}</div>
                <div id="sous_titre_corps">
                    Par
                    <a href="{{ env('FORMER_APP_URL') }}?p=profil&membre={{ $pre_test->user_id }}">
                        {{ $pre_test->user->name }}
                    </a>
                </div>
                <div class="barre_boutons">
                    <span class="bordure_boutons">
                        <a class="bouton voir-jeu" href="{{ $game->getUrl() }}">
                            Voir la fiche du jeu
                        </a>
                        <a class="bouton modifier-pre-test"
                           href="{{ route('pre_qualifications.edit', ['pre_test' => $pre_test->id]) }}">
                            Modifier
                        </a>
                    </span>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4>Critères disqualifiants</h4>

                        @foreach (App\PreTest::PRE_QUALIFICATIONS_DISQUALIFYING_FIELDS as $field)
                            @if (Illuminate\Support\Arr::has($pre_test->questionnaire, $field['id']))
                                @if ($pre_test->questionnaire[$field['id']]['activated'])
                                    <div class="questionnaire-group">
                                        <p><strong>{{ $field['label'] }}</strong></p>
                                        <div class="explanation">
                                            {{ $pre_test->questionnaire[$field['id']]['explanation'] }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach

                        <hr/>
                        <h4>Critères non disqualifiants</h4>

                        @foreach (App\PreTest::PRE_QUALIFICATIONS_NOT_DISQUALIFYING_FIELDS as $field)
                            @if (Illuminate\Support\Arr::has($pre_test->questionnaire, $field['id']))
                                @if ($pre_test->questionnaire[$field['id']]['activated'])
                                    <div class="questionnaire-group">
                                        <p><strong>{{ $field['label'] }}</strong></p>
                                        <div class="explanation">
                                            {{ $pre_test->questionnaire[$field['id']]['explanation'] }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach

                        @if($pre_test->questionnaireHasActivatedFields())
                            <hr/>
                        @endif

                        <h4>Verdict</h4>
                        <div id="final-thought">
                            <p class="final-thought {{ $pre_test->final_thought }}">
                                @if ($pre_test->final_thought == "ok")
                                    Conforme, sans aucun souci
                                @elseif ($pre_test->final_thought == "some-problems")
                                    Conforme avec des soucis non disqualifiants mais problématiques
                                @else
                                    Non conforme
                                @endif
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
