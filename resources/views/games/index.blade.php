@extends('layouts.app')

@section('title', 'Liste des jeux')

@push('stylesheets')
    <link href="{{ asset('css/games.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="games-section" id="games-list">
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
                    Les inscriptions sont ouvertes
                    jusqu'au {{ $currentSession->lastIncludedDayForGamesRegistration() }} inclus.
                @elseif($selectedSession)
                    Voici la liste des jeux inscrits lors de cette session.
                @else
                    Voici la liste des jeux inscrits depuis le début du concours.
                @endif

                Cliquez sur le titre d'un jeu si vous souhaitez en savoir plus le concernant.
            </p>
            <div class="container">
                <form method="GET" action="{{ route('jeux.index') }}" class="games-form">
                    <input name="sort" type="hidden" value="{{ old('sort') }}"/>
                    <div class="row">
                        <div class="col">
                            <div class="row gy-2">
                                <div class="col-md-6">
                                    <label for="session" class="form-label">Session</label>
                                    <select id="session" name="session_id" class="form-select">
                                        <option value="">(Toutes les sessions)</option>
                                        @foreach ($sessions as $session)
                                            <option
                                                    value="{{ $session->id_session }}"
                                                    {{ old("session_id") == $session->id_session ? "selected": "" }}
                                            >
                                                {{ $session->nom_session }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="software" class="form-label">Logiciels</label>
                                    <select id="software" name="software" class="form-select">
                                        <option value="">(Tous les logiciels)</option>
                                        @foreach ($softwares as $software)
                                            <option
                                                    value="{{$software}}"
                                                    {{ old("software") == $software ? "selected": "" }}
                                            >
                                                {{ $software }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="query" class="form-label">Recherche</label>
                                    <input
                                            id="query"
                                            name="q"
                                            class="form-control"
                                            type="text"
                                            placeholder="Aventure, Humour, RuTiPa's Quest, ..."
                                            value="{{ old('q') }}"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col gy-3">
                            <div class="form-check pt-4 mb-5">
                                <input
                                        id="download-links"
                                        name="download_links"
                                        class="form-check-input"
                                        type="checkbox"
                                        value="any"
                                        {{ old("download_links") == "any" ? "checked": "" }}
                                />
                                <label for="download-links" class="form-check-label">Avec lien de
                                    téléchargement</label>
                            </div>

                            <button class="bouton" type="submit">Rechercher</button>
                        </div>
                    </div>
                </form>

                @if($games->total() <= $games->perPage())
                    <p class="mb-4">Nombre de jeux : <strong>{{ $games->total() }}</strong>.</p>
                @else
                    <p class="mb-4">Nombre de jeux : <strong>{{ count($games) }} sur {{ $games->total() }}</strong>.</p>
                @endif
            </div>
            <table class="table">
                <tr class="tableau_legend">
                    <th></th>
                    <th class="title">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title']) }}">
                            Titre du Jeu
                        </a>
                    </th>
                    <th class="session">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'session_id']) }}">
                            Session
                        </a>
                    </th>
                    <th class="author">Auteur(s)</th>
                    <th class="software">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'software']) }}">
                            Support
                        </a>
                    </th>
                    <th class="genre">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'genre']) }}">
                            Genre
                        </a>
                    </th>
                    <th class="download">Téléch.</th>
                </tr>

                @foreach ($games as $game)
                    <x-game-row :game="$game"></x-game-row>
                @endforeach
            </table>

            {{-- TODO : Factorize --}}
            @php ($totalPages = ceil($games->total() / $games->perPage()))
            @if ($totalPages > 1)
                <nav aria-label="Pagination de la liste des jeux">
                    <ul class="pagination justify-content-center mt-4 mb-4">
                        <li class="page-item previous {{ $games->currentPage() == 1 ? "disabled" : "" }}">
                            <a class="page-link"
                               href="{{ request()->fullUrlWithQuery(['page' => $games->currentPage() - 1]) }}"
                               tabindex="-1">Précédente</a>
                        </li>
                        @for ($index = 1; $index <= $totalPages; $index++)
                            <li class="page-item {{ $games->currentPage() == $index ? "active" : "" }}">
                                @if ($games->currentPage() == $index)
                                    <a class="page-link">
                                        {{ $index }}
                                        <span class="visually-hidden">(current)</span>
                                    </a>
                                @else
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $index]) }}">
                                        {{ $index }}
                                    </a>
                                @endif
                            </li>
                        @endfor
                        <li class="page-item next {{ $games->currentPage() == $games->lastPage() ? "disabled" : "" }}">
                            <a class="page-link"
                               href="{{ request()->fullUrlWithQuery(['page' => $games->currentPage() + 1]) }}">Suivante</a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
@stop
