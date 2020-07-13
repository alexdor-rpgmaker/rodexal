<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
  <!--<![endif]-->
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', "Alex d'or") }}</title>

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
  <body style="margin-bottom: 0;">
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
  </body>
</html>
