@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/pre-tests.js') }}" defer></script>
    <script>
        const game = @json($game);
        const questions = @json(App\PreTest::FIELDS);
        const redirectionUrl = @json(env('FORMER_APP_URL').'/?p=mes-tests');
    </script>
@endpush

@section('content')
    <div class="container">
        <div id="pre-tests-form" class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">QCM du jeu {{ $game['title'] }}</div>

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
