@extends('layouts.app')

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/games.js') }}" defer></script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="games-wrapper" class="col-md-10">
                <games :session='@json($sessionId)' />
            </div>
        </div>
    </div>
@stop
