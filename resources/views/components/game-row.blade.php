<tr class="tr">
    {{-- TODO : Move "border: none;" in CSS file (x3) --}}
    <td>
        @if($game->screenshots->isNotEmpty())
            <a href="{{ $game->getUrl() }}" class="screenshot-link">
                <img src="{{ $game->screenshots[0]->getImageUrlForSession($game->id_session) }}"
                     style="border: none;"
                     class="screenshot"
                     width="100"
                     alt=""
                />
            </a>
        @endif
    </td>
    <td>
        <p class="title mb-1">
            <a href="{{ $game->getUrl() }}" class="title-link">
                {{ $game->nom_jeu }}
            </a>
        </p>
        @if($wasAwarded())
            <p class="awarded-categories mb-1">
                Victoire : {{ $awardedCategoriesList }}
            </p>
        @endif
        @if($wasNominated())
            <p class="nominated-categories mb-1">
                Nominations : {{ $nominatedCategoriesList }}
            </p>
        @endif
    </td>
    <td class="session">{{ $game->session->nom_session }}</td>
    <td class="makers">
        @if($game->creationGroup)
            <span>{{ $game->groupe }} :</span>
        @endif
        {!! $contributors !!}
    </td>
    <td class="software">{{ $game->support }}</td>
    <td class="genre">{{ $game->genre_jeu }}</td>
    <td class="download-links">
        @if($game->hasWindowsDownloadLink())
            <a href="{{ $game->getWindowsDownloadLink() }}">
                <img src="{{ env('FORMER_APP_URL') }}/design/divers/disquette-verte.gif"
                     style="border: none;"
                     alt="Disquette"
                />
                (Win)
            </a>
        @endif
        @if($game->hasMacDownloadLink())
            <a href="{{ $game->getMacDownloadLink() }}">
                <img src="{{ env('FORMER_APP_URL') }}/design/divers/disquette-verte.gif"
                     style="border: none;"
                     alt="Disquette"
                />
                (Mac)
            </a>
        @endif
    </td>
</tr>
