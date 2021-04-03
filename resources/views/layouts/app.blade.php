<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
  <!--<![endif]-->
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
      @hasSection('title')
        @yield('title') - {{ config('app.name', "Alex d'or") }}
      @else
        {{ config('app.name', "Alex d'or") }}
      @endif
    </title>

    <script type="text/javascript">
      window.debug = @json(env('APP_DEBUG'));
      window.formerAppUrl = @json(env('FORMER_APP_URL'));
    </script>

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')

    <!-- Font Awesome -->
    <!-- TODO : Import with npm? -->
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
      integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
      crossorigin="anonymous"
    />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('stylesheets')

    <link
      rel="stylesheet"
      media="screen"
      type="text/css"
      title="global"
      href="{{ env('FORMER_APP_URL') }}/archives/news/vieilles_news.css"
    />

    <link
      rel="alternate"
      type="application/rss+xml"
      title="Derni&egrave;rs Jeux"
      href="{{ env('FORMER_APP_URL') }}/flux/jeux.xml"
    />
    <link
      rel="alternate"
      type="application/rss+xml"
      title="Derni&egrave;res News"
      href="{{ env('FORMER_APP_URL') }}/flux/news.xml"
    />
    <link
      rel="alternate"
      type="application/rss+xml"
      title="Blog"
      href="{{ env('FORMER_APP_URL') }}/flux/blog.xml"
    />

    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="{{ env('FORMER_APP_URL') }}/design/divers/alexdor-ico.png"
    />
    <link
      rel="stylesheet"
      href="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/icones/stylesheets/general_foundicons.css"
    />

    <!--[if lt IE 8]>
      <link
        rel="stylesheet"
        href="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/icones/stylesheets/general_foundicons_ie7.css"
      />
    <![endif]-->

    <link
      type="text/css"
      href="{{ env('FORMER_APP_URL') }}/design/divers/jqueryUI/css/ui-lightness/jquery-ui-1.8.18.custom.css"
      rel="Stylesheet"
    />

    <base href="{{ config('app.url') }}" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta
      name="google-site-verification"
      content="eQkiRs05no4-CMDllsNRWKg3dWOqgI54OVE_49sAvWE"
    />

    <meta property="og:site_name" content="Alex d'or" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@AlexDorRM" />
    <meta name="twitter:domain" content="alexdor.info" />

    <script type="text/javascript">
      <!-- Google Analytics -->
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-36145862-1', 'auto');
        ga('send', 'pageview');
    </script>
    <link
      rel="stylesheet"
      href="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/css/normalize.css"
    />
    <link
      rel="stylesheet"
      href="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/css/foundation.css"
    />
    <link rel="stylesheet" href="{{ env('FORMER_APP_URL') }}/design/newdefaut/interface.css" />
    <link rel="stylesheet" href="{{ env('FORMER_APP_URL') }}/design/newdefaut/elements.css" />

    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/vendor/custom.modernizr.js"></script>
  </head>
  <body>
    <div id="wrap">
      <nav class="top-bar">
        <ul class="title-area">
          <!-- Title Area -->
          <li class="name">
            <a href="{{ env('FORMER_APP_URL') }}/">
              <img
                src="{{ env('FORMER_APP_URL') }}/design/newdefaut/interface/ban.png"
                width="266"
                height="44"
              />
            </a>
          </li>
          <li class="toggle-topbar menu-icon">
{{--            TODO : Remove Request::server('HTTP_HOST').Request::server('REQUEST_URI') ?--}}
            <a href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#"><span>menu</span></a>
          </li>
        </ul>

        <section class="top-bar-section">
          <!-- Right Nav Section -->
          <ul class="left">
            <li class="has-dropdown">
              <a href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#">Actualités</a>
              <ul class="dropdown">
                <!-- <li><a href="{{ env('FORMER_APP_URL') }}/">Accueil</a></li> -->
                <li><a href="{{ env('FORMER_APP_URL') }}?p=news">News</a></li>
                <li><a href="{{ route('podcast.index') }}?p=news">Podcast</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=blog">Blog</a></li>
              </ul>
            </li>

            <li class="has-dropdown">
              <a href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#">Session 2021</a>
              <ul class="dropdown">
                <li><a href="{{ env('FORMER_APP_URL') }}?p=session&session=21">Informations</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=liste-jeux">Jeux en lice</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=inscjeu">Inscrire un jeu</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=reglement">Règlement</a></li>
              </ul>
            </li>

            <li class="has-dropdown">
              <a href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#">Archives</a>
              <ul class="dropdown">
                <li><a href="{{ env('FORMER_APP_URL') }}?p=archives">Toutes les archives</a></li>
                <li class="divider"></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=archives-vainqueurs">Jeux vainqueurs</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=liste-jeux&amp;session=0">Tous les jeux</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=series-jeux">Séries de jeux</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=jeu">Un jeu au hasard</a></li>
              </ul>
            </li>

            <li class="has-dropdown">
              <a href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#">Forum</a>
              <ul class="dropdown">
                <li><a href="{{ env('FORMER_APP_URL') }}?p=forum-categories">Index du forum</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=sujets&amp;nouveau">Nouveaux messages</a></li>
              </ul>
            </li>

            <li class="has-dropdown">
              <a href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#">Communauté</a>
              <ul class="dropdown">
                <li><a href="{{ route('dictionnaire.index') }}">Dictionnaire</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=liste-membres">Liste des membres</a></li>
                <li>
                  <a href="{{ env('FORMER_APP_URL') }}?p=derniers-commentaires">Derniers commentaires</a>
                </li>
              </ul>
            </li>

            @if (Auth::user() && Auth::user()->isAdmin())
              <li class="has-dropdown">
                <a href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#">Admin</a>
                <ul class="dropdown">
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-general">Général</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-news">News</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-blog">Blog</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-images">Images</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-carrousel">Carrousel</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-categories">Forum</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-membres">Membres</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=mail-membres">Envoi Mails</a></li>
                  <li class="divider"></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-sessions">Sessions</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-jeux">Jeux</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-jurys">Jury</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-series">Tests</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-notes">Notes</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-deliberations">Délibérations</a></li>
                  <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-awards">Awards</a></li>
                </ul>
              </li>
            @endif

            <li class="name" id="header-recherche">
              <form action="?p=recherche" method="post">
                <input
                  type="text"
                  name="recherche_champ"
                  style="width:140px;"
                  placeholder="Rechercher"
                />
                <input type="hidden" name="recherche_type" value="tous" />
              </form>
            </li>
          </ul>

          <ul class="right">
            @auth

                <li>
                    <a href="?p=mp-messagerie" onFocus="this.blur();" title="Messagerie privée">
                    <img src="{{ env('FORMER_APP_URL') }}/design/newdefaut/interface/mp-transp.png" width="26" height="20" alt="Messagerie privée"/></a>
                </li>

                <li class="has-dropdown">
                    <a class="couleur_{{ Auth::user()->rankName() }}" href="{{ env('FORMER_APP_URL') }}?p=profil&amp;membre={{ Auth::id() }}">
                        @if (session('resource-owner') && session('resource-owner')['avatar_url'])
                            <img class="header-avatar" src="{{ session('resource-owner')['avatar_url'] }}" alt="avatar">
                        @endif
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown">
                        <li><a href="{{ env('FORMER_APP_URL') }}?p=panneau-membre">Mon Profil</a></li>
                        <li><a href="{{ env('FORMER_APP_URL') }}?p=mes-jeux">Mes Jeux</a></li>
                        <li><a href="{{ env('FORMER_APP_URL') }}?p=mes-tests">Mes Tests</a></li>
                        <li><a href="{{ env('FORMER_APP_URL') }}?p=mon-classement">Mon Classement</a></li>
                        <li><a href="{{ env('FORMER_APP_URL') }}?p=mes-videos">Mes Vidéos</a></li>
                        <li class="has-dropdown">
                            <a href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#" class="">Mon Design</a>
                            <ul class="dropdown">
                                <li><a href="{{ env('FORMER_APP_URL') }}?changement-design=5">Nouveau (2013)</a></li>
                                <li><a href="{{ env('FORMER_APP_URL') }}?changement-design=0">Lifaen</a></li>
                                <li><a href="{{ env('FORMER_APP_URL') }}?changement-design=1">Walina &amp; Khoryl</a></li>
                                <li><a href="{{ env('FORMER_APP_URL') }}?changement-design=2">Papillon</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ env('FORMER_APP_URL') }}?p=deconnexion">Déconnexion</a></li>
                    </ul>
                </li>

            @else

                <li>
                    {{-- <a href="?p=connexion">Se connecter</a> --}}
                    <a href="{{ url('/oauth/callback') }}">Se connecter</a>
                </li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=inscription">S'inscrire</a></li>

            @endauth
          </ul>
        </section>
      </nav>
      <!-- Fil d'Ariane -->

      <div id="navigateur">
        <a href="{{ env('FORMER_APP_URL') }}">Alex d'or</a>
      </div>

      <div class="row" id="mainrow">
        <main class="py-4">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            @if (session('status'))
                <div class="container">
                    <div class="message_info">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
      </div>
    </div>

    <!-- Footer -->

    <div id="footer">
      <div id="footer-part1">
        <div class="large-12 columns">
          <div class="row">
            <div class="large-12 columns">
                @php
                    $connected_users = mt_rand(1, 5);
                    $unconnected_visitors = mt_rand(3, 10);
                    $all_visitors = $connected_users + $unconnected_visitors
                @endphp
                {{ $all_visitors }} visiteurs connectés ({{ $connected_users }} membres et {{ $unconnected_visitors }} anonymes)
            </div>
          </div>
        </div>
      </div>
      <div id="footer-part2">
        <div class="large-12 columns">
          <div class="row">
            <div class="large-6 columns">
              Hébergé par
              <a href="https://www.alexzone.net">AlexZone.net</a>.<br />
              La page a été calculée en 0,124025 secondes. <br />15 requêtes
              effectuées.
            </div>

            <div class="large-6 small-12 columns">
              <ul class="inline-list right">
                <li><a href="{{ env('FORMER_APP_URL') }}?p=liste-jeux">Jeux</a></li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=forum-categories">Forum</a></li>
                <li>
                  <a
                    onclick="window.open('{{ env('FORMER_APP_URL') }}/jkbx.php', 'alexdor_jkbx', 'toolbar=no, status=no, scrollbars=no, resizable=no, width=370, height=450');return(false)%22"
                    title="Clique ici pour écouter !"
                    href="{{ "https://".Request::server('HTTP_HOST').Request::server('REQUEST_URI') }}#"
                    >Jukebox</a
                  >
                </li>
                <li><a href="{{ env('FORMER_APP_URL') }}?p=contact">Contacter l'équipe</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Included JS Files (Uncompressed) -->

    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/vendor/zepto.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.clearing.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.orbit.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.section.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.topbar.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.dropdown.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.cookie.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.alerts.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.forms.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.magellan.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.placeholder.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.reveal.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.tooltips.js"></script>
    <script src="{{ env('FORMER_APP_URL') }}/design/newdefaut/foundation/js/foundation/foundation.joyride.js"></script>

    <script>
      $(document).foundation();
    </script>
  </body>
</html>
