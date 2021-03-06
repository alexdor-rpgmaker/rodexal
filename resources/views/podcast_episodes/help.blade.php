@extends('layouts.app')

@section('title', 'Comment écouter le podcast ?')

@section('content')
    <div id="titre_corps">
        Pod-Alex
    </div>
    <div id="sous_titre_corps">
        Comment écouter le podcast ?
    </div>
    <script>
      function copyPodcastUrlField() {
        const copyText = document.getElementById('podcast-url')

        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        document.execCommand("copy");
      }
    </script>
    <div id="corps">
        <h4>Comment écouter le podcast des Alex d'or ?</h4>
        <p>
            Vous pouvez l'écouter directement sur ce site <a href="{{ route('podcast.index') }}">dans la section Podcast</a>.
        </p>
        <p>
            En outre, de nombreuses applications vous permettent d'écouter des podcasts, <a href="https://open.spotify.com/show/7zJ7ddmiAFjFJHgyJhJGiB">comme par exemple Spotify</a>.
            Vous pouvez consulter la liste de ces applications sur <a href="{{ App\PodcastEpisode::PODCAST_PAGE_URL }}">la page Anchor de Pod-Alex</a>.
        </p>
        <p>
            Si vous avez déjà une application favorite
            (par exemple <a href="https://play.google.com/store/apps/details?id=com.bambuna.podcastaddict&hl=fr">Podcast Addict</a> sur Android),
            vous pouvez directement y copier-coller l'URL du flux RSS du podcast :
        </p>
        <div class="input-group mb-2">
            <input type="text" value="{{ App\PodcastEpisode::PODCAST_FEED_URL }}" id="podcast-url" class="form-control" readonly>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="copyPodcastUrlField()">Copier</button>
            </div>
        </div>
        <p>
            Les nouveaux épisodes seront affichés directement dans l'interface et vous pouvez même les télécharger automatiquement sur votre appareil !
        </p>
    </div>
@stop
