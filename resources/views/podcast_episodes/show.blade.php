@extends('layouts.app')

@section('title', $podcastEpisode->title.' - Podcast')

@push('stylesheets')
    <link href="{{ asset('css/podcasts.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="podcast" class="col-md-8">
                <div id="titre_corps">
                    Pod-Alex
                </div>
                <div id="sous_titre_corps"></div>
                <div class="card">
                    <div class="card-body">
                        <p>Saison {{ $podcastEpisode->seasonAndSession() }}</p>
                        <h1>{{ $podcastEpisode->title }}</h1>
                        <p>le {{ $podcastEpisode->created_at->format('d/m/Y') }}</p>
                        <ul>
                            <li>Auteur : {!! $author->getLink() !!}</li>
                            <li>Durée : {{ $podcastEpisode->duration() }}</li>
                        </ul>
                        <audio
                                controls
                                src="{{ $podcastEpisode->audio_url }}">
                            Votre navigateur ne supporte pas l'élément HTML <code>audio</code>. :(
                        </audio>
                        <p>{{ $podcastEpisode->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
