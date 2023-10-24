@extends('layouts.app')

@section('title', "$game_title - Pré-qualification")

@section('content')
    <div class="container">
        <div id="pre-tests-form" class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Pré-qualification pour le jeu <em>{{ $game_title }}</em>
                    </div>

                    <div class="card-body">
                        @include('common.errors')

                        <p>Après avoir joué 20 minutes au jeu, remplissez ce questionnaire avant d'aller plus loin.</p>
                        <pre-qualifications-form
                                :questions-on-disqualifying-subjects='@json(App\PreTest::PRE_QUALIFICATIONS_DISQUALIFYING_FIELDS)'
                                :questions-on-not-disqualifying-subjects='@json(App\PreTest::PRE_QUALIFICATIONS_NOT_DISQUALIFYING_FIELDS)'
                                :game-id='@json($game_id)'
                                :pre-qualification='@json($pre_test)'
                                :init-method='@json($form_method)'
                                :init-action='@json($form_url)'
                                :init-redirection='@json(env('FORMER_APP_URL').'/?p=mes-tests&message=Enregistrement effectué !')'>
                        </pre-qualifications-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
