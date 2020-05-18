@extends('layouts.app')

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
    {{-- TODO : Move style in css file --}}
    <style>
        .author {
            width: 160px;
        }

        .software, .genre {
            width: 130px;
        }

        .download {
            width: 50px;
        }

        .page-item .page-link {
            color: #d39501;
        }

        .page-item.active .page-link {
            color: white;
            border-color: #d39501;
            background-color: #d39501;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
        }

        .tr td {
            text-align: left;
        }

        .color-member,
        .color-challenger,
        .color-ambassador,
        .color-juror,
        .color-moderator,
        .color-administrator,
        .color-webmaster {
            font-weight: bold;
        }

        .color-member {
            color: #2954ff;
        }

        .color-challenger {
            color: #0b00a1;
        }

        .color-ambassador {
            color: #00acb8;
        }

        .color-juror {
            color: #9f3ad1;
        }

        .color-moderator {
            color: #269600;
        }

        .color-administrator,
        .color-webmaster {
            color: #cf0000;
        }

        .awarded-categories, .nominated-categories {
            font-style: italic;
        }
    </style>
    <div id="corps">
        <p class="p-2">
            @if($selectedSession == $currentSession)
                Voici la liste des jeux inscrits à la session actuelle.
                Les inscriptions sont ouvertes
                jusqu'au {{ $currentSession->date_cloture_inscriptions->subDay()->format('d/m/Y') }} inclus.
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="session">Session</label>
                                    <select id="session" name="session_id" class="custom-select">
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
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="software">Logiciels</label>
                                    <select id="software" name="software" class="custom-select">
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="query">Recherche</label>
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
                    </div>
                    <div class="col">
                        <div class="form-check form-group pt-4 mb-5">
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
                                    <span class="sr-only">(current)</span>
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
@stop
