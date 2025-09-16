@extends('layouts.app')

@section('title', 'Podcast')

@section('content')
    <div id="titre_corps">
        Pod-Alex
    </div>
    <div id="sous_titre_corps">
        Le podcast officiel des Alex d'or !
    </div>
    <div id="corps">
        <p>
            En 2021 et 2022, le Podcast des Alex d'or, baptisé <strong>Pod-Alex</strong>, nous a permis d'en savoir
            plus sur les jeux présentés lors de ces sessions ainsi que leurs créateurs.
        </p>
        <p>
            Vous pouvez retrouver tous les épisodes ci-dessous, ainsi que quelques liens utiles.
        </p>
        <ul>
            <li>
                <a href="{{ App\Former\Game::getListUrl(22) }}">
                    Jeux de la session {{ App\Former\Session::nameFromId(22) }}
                </a>
            </li>
            <li>
                <a href="{{ App\Former\Game::getListUrl(21) }}">
                    Jeux de la session {{ App\Former\Session::nameFromId(21) }}
                </a>
            </li>
            <li>
                <a href="{{ route('podcast.help') }}">Comment écouter le podcast ?</a>
            </li>
        </ul>
        <h4 class="mb-4">Episodes</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Durée</th>
                    <th>Date de publication</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentSeason = null;
                @endphp
                @foreach ($podcastEpisodes as $podcastEpisode)
                    @if ($currentSeason != $podcastEpisode->season)
                        @if (!$loop->first)
                            </tbody>
                        @endif
                        <thead>
                            <tr>
                                <th colspan="4">
                                    Saison {{ $podcastEpisode->season }}
                                    (Session {{ $podcastEpisode->session() }})
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $currentSeason = $podcastEpisode->season;
                        @endphp
                    @endif
                    <tr>
                        <td>
                            <a href="{{ route('podcast.show', $podcastEpisode) }}">
                                {{$podcastEpisode->title}}
                            </a>
                        </td>
                        <td>
                            {!! $podcastEpisode->author->getLink() !!}
                        </td>
                        <td>
                            {{$podcastEpisode->duration()}}
                        </td>
                        <td>
                            {{$podcastEpisode->created_at->format('d/m/Y')}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
