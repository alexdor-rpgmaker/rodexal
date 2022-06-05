@extends('layouts.app')

@section('title', 'Inscrire un jeu')

@push('stylesheets')
    <link href="{{ asset('css/games.css') }}" rel="stylesheet">
@endpush

@section('content')
    @if($currentSession->tooLateForGamesRegistration())
        <div class="message_erreur">
            @php
                /** @noinspection PhpUndefinedVariableInspection */
                $nextSessionName = App\Former\Session::nameFromId($currentSession->id_session + 1);
            @endphp
            Il est trop tard pour inscrire son jeu. Attendez la session {{$nextSessionName}} !
        </div>
    @endif
    <div class="games-section" id="games-form">
        <div id="titre_corps">{{ $title }}</div>
        <div id="sous_titre_corps">{{ $subtitle }}</div>

        <div id="corps">
            <div class="p-2">
                <p>
                    Inscrire son jeu au concours, c'est le faire découvrir à la communauté RPG Maker et notamment
                    aux jurés qui se chargeront de l'évaluer et éventuellement de lui remettre la plus haute distinction
                    : l'Alex d'or...
                </p>
                <p>
                    Avant de proposer votre participation au concours, assurez-vous d'avoir bien consulté le <a
                            href="{{env('FORMER_APP_URL')}}?p=reglement" target="_blank">règlement</a>.
                </p>
            </div>

            @if($currentSession->etape == 0)
                <div class="message_info">
                    Les inscriptions vont reprendre dans quelques jours !
                </div>
            @endif

            @if($currentSession->tooLateForGamesRegistration())
                <div class="message_info">
                    Les inscriptions sont terminées !
                </div>
            @endif

            @if($currentSession->allowsGamesRegistration())
                <div class="message_info">
                    Les inscriptions sont ouvertes jusqu'au
                    {{ $currentSession->lastIncludedDayForGamesRegistration() }} inclus !
                </div>

                @if($currentSession->gamesRegistrationEndsInLessThanSevenDays())
                    <div class="message_important">
                        Attention, vous n'avez encore que quelques jours pour vous inscrire et indiquer un lien de
                        téléchargement valide pour votre jeu !
                    </div>
                @endif

                @guest
                    <div class="message_important">
                        Vous devez être inscrit et connecté pour proposer un jeu.
                    </div>

                    <p>
                        <a href="{{ App\User::signUpUrl() }}" class="bouton">S'inscrire</a>
                        <a href="{{ App\User::signInUrl() }}" class="bouton">Se connecter</a>
                    </p>
                @else
                    <div class="container">
                        @include('common.errors')

                        <form method="POST" action="{{ $form_url }}" class="games-form">
                            @method($form_method)
                            @csrf

                            <fieldset class="fieldset">
                                <legend class="legend">Généralités</legend>
                                <table class="mb-0">
                                    <tr>
                                        <td width="200">
                                            <label for="title">
                                                Nom du jeu<span class="obligatoire">*</span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <input id="title"
                                                       name="title"
                                                       value="{{ old('title', $game->nom_jeu) }}"
                                                       class="input_text_large form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                       autofocus
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Avancement du projet<span class="obligatoire">*</span>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input id="progression-demo"
                                                       name="progression"
                                                       type="radio"
                                                       value="demo"
                                                       @if(old('progression') == 'demo') checked @endif
                                                       class="form-check-input{{ $errors->has('progression') ? ' is-invalid' : '' }}"
                                                />
                                                <label class="form-check-label" for="progression-demo">
                                                    Démo
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input id="progression-full"
                                                       name="progression"
                                                       type="radio"
                                                       value="full"
                                                       @if(old('progression') == 'full') checked @endif
                                                       class="form-check-input{{ $errors->has('progression') ? ' is-invalid' : '' }}"
                                                />
                                                <label class="form-check-label" for="progression-full">
                                                    Jeu complet
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="software">
                                                Support de création<span class="obligatoire">*</span>
                                            </label>
                                        </td>
                                        <td>
                                            <select-software
                                                    initial-software="{{ old('software') }}"
                                                    :software-list='@json(App\Former\Game::SOFTWARE_LIST)'
                                                    :registration-allowed="@json($currentSession->allowsGamesRegistration())"></select-software>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>

                            <fieldset class="fieldset">
                                <legend class="legend">
                                    Description<span class="obligatoire">*</span>
                                </legend>
                                <p class="couleur_indications">
                                    Une présentation complète mais concise de votre jeu.
                                    Décrivez les points clé, pensez à parler du gameplay.
                                    Ne donnez pas l'ensemble des détails de l'histoire, l'univers et les personnages,
                                    laissez un peu de surprise aux joueurs.
                                    Ne mettez pas une liste de screenshots ici, utilisez le module disponible une fois
                                    que vous aurez confirmé ce formulaire.
                                </p>
                                <table style="margin-top: 10px">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            Balises
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="float:left;width:100px;text-align:center;">
                                            Smileys
                                        </td>
                                        <td>
                                        <textarea
                                                id="description"
                                                name="description"
                                                class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                rows="15"
                                                cols="60"
                                        >{{ old('description', $game->description) }}</textarea><br/>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>

                            <div class="message_info">
                                Une fois l'inscription confirmée, vous pourrez gérer les participants et les screenshots
                                via une page spécifique.
                            </div>

                            <button type="submit" class="submit bouton">
                                Envoyer
                            </button>
                        </form>
                    </div>
                @endguest
            @endif
        </div>
    </div>
@stop
