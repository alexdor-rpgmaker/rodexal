@extends('layouts.app')

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/pre-tests.js') }}" defer></script>
    <script type="text/javascript">
        const initQuestions = @json(App\PreTest::FIELDS);
        const initMethod = @json($form_method);
        const initAction = @json($form_url);
        const initGameId = @json($game_id);
        const initPreTest = @json($pre_test);
        const initRedirection = @json(env('FORMER_APP_URL').'/?p=mes-tests&message=Enregistrement effectué !');
    </script>
@endpush

@section('content')
    <div class="container">
        <div id="pre-tests-form" class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>

                    <div class="card-body">
                        @include('common.errors')

                        <p>Après avoir joué 30 minutes au jeu, remplissez ce questionnaire avant d'aller plus loin.</p>
                        <pre-tests-form></pre-tests-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
