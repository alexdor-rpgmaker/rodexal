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
            Grande nouveauté de la session précédente : le Podcast des Alex d'or, baptisé <strong>Pod-Alex</strong>.
            Chaque épisode audio est l'occasion de découvrir un des
            <a href="{{ App\Former\Game::getListUrl() }}"> jeux en lice</a> ainsi que leurs créateurs.
        </p>
        <p>
            <a href="{{ route('podcast.help') }}">Comment écouter le podcast ?</a>
        </p>
        <h4 class="mb-4">Episodes</h4>
        <table class="table">
            <thead>
            <tr>
                <th>Saison (Session)</th>
                <th>Titre</th>
                <th>Date de publication</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($podcastEpisodes as $podcastEpisode)
                <tr>
                    <td>
                        {{$podcastEpisode->seasonAndSession()}}
                    </td>
                    <td>
                        <a href="{{ route('podcast.show', $podcastEpisode) }}">
                            {{$podcastEpisode->title}}
                        </a>
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
