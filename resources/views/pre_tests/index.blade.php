@extends('layouts.app')

@section('title', 'Pré-Qualifications & QCM de la session '. $session->name())

@section('content')
    <div id="titre_corps">
        Pré-Qualifications & QCM
    </div>
    <div id="sous_titre_corps">
        Liste des pré-qualifications ou QCM de la session {{ $session->name() }}
    </div>
    <div id="corps">
        <session-change :session-ids="@json(App\Former\Session::IDS_SESSIONS_WITH_PRE_TESTS)"
                        :initial-session-id="{{$session->id_session}}"></session-change>

        <p><a href="{{App\Former\Test::getListUrl($session->id_session)}}">Voir les tests</a></p>

        @if($session == $currentSession && !$session->preTestsAreFinished())
            <p>Les Pré-Qualifications/QCM affichés ici concernent uniquement les jeux disqualifiés.</p>
        @endif

        @foreach ($games as $game)
            <p><strong>{{$game->nom_jeu}}</strong></p>

            <ul>
                @foreach ($preTestsByGameId->get($game->id_jeu) as $preTest)
                    <li>
                        @if($preTest->type == 'qcm')
                            <a href="{{ route('qcm.show', $preTest) }}">
                                QCM de {{$preTest->user->name}}
                            </a>
                        @else
                            <a href="{{ route('pre_qualifications.show', $preTest) }}">
                                Pré-Qualifications de {{$preTest->user->name}}
                            </a>
                        @endif
                        -
                        <span class="final-thought {{ $preTest->final_thought }}">
                            @if($preTest->final_thought == 'ok')
                                Conforme
                            @elseif($preTest->final_thought == 'some-problems')
                                Conforme avec des soucis non disqualifiants mais problématiques
                            @else
                                Non conforme
                            @endif
                        </span>
                        @if($preTest->explanationsCount() > 0)
                            - {{ $preTest->explanationsCount() }} remarque(s)
                        @endif
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
@stop
