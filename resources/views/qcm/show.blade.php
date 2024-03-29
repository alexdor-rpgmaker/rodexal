@extends('layouts.app')

@section('title')
    QCM de {{ $game->nom_jeu }} par {{ $pre_test->user->name }}
@stop

@push('stylesheets')
    <link href="{{ asset('css/qcm.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="qcm" class="col-md-8">
                <div id="titre_corps">QCM : {{ $game->nom_jeu }}</div>
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
                           href="{{ route('qcm.edit', ['pre_test' => $pre_test->id]) }}">
                            Modifier
                        </a>
                    </span>
                </div>
                <div class="card">
                    <div class="card-body">
                        @foreach (App\PreTest::QCM_FIELDS as $field)
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

                        <div id="final-thought">
                            <p>
                                Verdict :
                                <span class="final-thought {{ $pre_test->final_thought }}">
                                    {{ $pre_test->final_thought == 'ok' ? 'Conforme' : 'Non conforme' }}
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
