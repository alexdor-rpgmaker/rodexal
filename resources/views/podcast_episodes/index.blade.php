@extends('layouts.app')

@section('title', 'Podcast')

@section('content')
    <div id="titre_corps">
        Podcast
    </div>
    <div id="sous_titre_corps">
        Voici les épisodes du podcast officiel des Alex d'or !
    </div>
    <div id="corps-invisible">
        <p style="margin-left: 20px;">
            <a href="{{ route('feeds.podcast') }}" class="bouton">
                <i class="fa fa-rss"></i> Flux RSS
            </a>
        </p>
        <table class="table">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($podcastEpisodes as $podcastEpisode)
                <tr>
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
