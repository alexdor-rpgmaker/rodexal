@extends('layouts.app')

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/games.js') }}" defer></script>
@endpush

@section('content')
    <div id="titre_corps">
        @if($sessionId)
            Liste des jeux de la {{ $sessionName }}
        @else
            Liste des jeux de toutes les sessions
        @endif
    </div>
    <div id="sous_titre_corps">
        @if($sessionId)
            Inscrits, vainqueurs, et nominés de cette session
        @else
            Inscrits, vainqueurs, et nominés de toutes les sessions
        @endif
    </div>
    <div id="corps">
        <p class="p-2">
            @if($sessionId)
                Voici la liste des jeux inscrits lors de cette session.
            @else
                Voici la liste des jeux inscrits depuis le début du concours.
            @endif

            Cliquez sur le titre d'un jeu si vous souhaitez en savoir plus le concernant.
        </p>
        <div id="games-wrapper">
            <games session='@json($sessionId)' />
        </div>
    </div>
@stop
