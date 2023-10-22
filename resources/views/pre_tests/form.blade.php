@extends('layouts.app')

@section('title', "$game_title - Pré-test")

@section('content')
    <div class="container">
        <div id="pre-tests-form" class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        QCM pour le jeu <em>{{ $game_title }}</em>
                    </div>

                    <div class="card-body">
                        @include('common.errors')

                        <p>Après avoir joué 20 minutes au jeu, remplissez ce questionnaire avant d'aller plus loin.</p>
                        <pre-tests-form
                                :questions='@json(App\PreTest::QCM_FIELDS)'
                                :game-id='@json($game_id)'
                                :pre-test='@json($pre_test)'
                                :init-method='@json($form_method)'
                                :init-action='@json($form_url)'
                                :init-redirection='@json(env('FORMER_APP_URL').'/?p=mes-tests&message=Enregistrement effectué !')'>
                        </pre-tests-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
