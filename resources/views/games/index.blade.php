@extends('layouts.app')

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/games.js') }}" defer></script>
@endpush

@section('content')
    <div id="titre_corps">
        @if($selectedSession)
            Liste des jeux de la {{ $selectedSession->nom_session }}
        @else
            Liste des jeux de toutes les sessions
        @endif
    </div>
    <div id="sous_titre_corps">
        @if($selectedSession)
            Inscrits, vainqueurs, et nominés de cette session
        @else
            Inscrits, vainqueurs, et nominés de toutes les sessions
        @endif
    </div>
    <div id="corps">
        <p class="p-2">
            @if($selectedSession == $currentSession)
                Voici la liste des jeux inscrits à la session actuelle.
                Les inscriptions sont ouvertes jusqu'au {{ $currentSession->date_cloture_inscriptions->subDay()->format('d/m/Y') }} inclus.
            @elseif($selectedSession)
                Voici la liste des jeux inscrits lors de cette session.
            @else
                Voici la liste des jeux inscrits depuis le début du concours.
            @endif

            Cliquez sur le titre d'un jeu si vous souhaitez en savoir plus le concernant.
        </p>

        <div id="games-wrapper">
            <games :session='@json($selectedSession ? $selectedSession->id_session : null)' />
        </div>
    </div>
@stop
