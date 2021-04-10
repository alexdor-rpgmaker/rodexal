@extends('layouts.app')

@section('title', 'QCM de la session '. $session->name())

@section('content')
    <div id="titre_corps">
        QCM
    </div>
    <div id="sous_titre_corps">
        Liste des QCM de la session {{ $session->name() }}
    </div>
    <div id="corps">
        <session-change :session-ids="@json(App\Former\Session::IDS_SESSIONS_WITH_QCM)"
                        :initial-session-id="{{$session->id_session}}"></session-change>

        <p><a href="{{App\Former\Test::getListUrl($session->id_session)}}">Voir les tests</a></p>

        @if($session == $currentSession && !$session->preTestsAreFinished())
            <p>Les QCM affichés ici correspondent à ceux des jeux disqualifiés.</p>
        @endif

        @foreach ($games as $game)
            <p>{{$game->nom_jeu}}</p>

            <ul>
                @foreach ($preTestsByGameId->get($game->id_jeu) as $preTest)
                    <li>
                        <a href="{{ route('qcm.show', $preTest) }}">
                            QCM de {{$preTest->user->name}}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
@stop
