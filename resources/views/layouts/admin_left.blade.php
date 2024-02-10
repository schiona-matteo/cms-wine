<!doctype html>
<html class="h-full bg-white">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="apple-touch-icon" sizes="57x57" href="/imgs/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/imgs/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/imgs/favicons/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/imgs/favicons/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/imgs/favicons/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/imgs/favicons/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/imgs/favicons/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/imgs/favicons/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/imgs/favicons/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="/imgs/favicons/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/imgs/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/imgs/favicons/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/imgs/favicons/favicon-16x16.png">
  <link rel="manifest" href="/imgs/favicons/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/imgs/favicons/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
  <title>{{ config('app.name') }} - Backoffice</title>

  <!-- Scripts -->
  @yield('scripts')
  <script src="{{ mix('js/backoffice.js') }}" defer></script>
  
  <!-- Styles -->
  @yield('styles')
  <link href="{{ mix('css/backoffice.css') }}" rel="stylesheet">
</head>
<body class="h-full">
  <div id="app">
    <notifications></notifications>
    <header-menu-left current="{{ request()->route()->getName() }}" :user="{{ json_encode(auth()->user()->only(['name', 'email'])) }}"></header-menu-left>
    <div class="md:pl-64 flex flex-col flex-1">
        <header-menu-left-button></header-menu-left-button>
        <main class="flex-1">
          <div class="py-6">
            @section('header')
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
              <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
            </div>
            @show
            <div class="max-w-8xl mx-auto px-4 sm:px-6 md:px-8">
              @section('content')
              <!-- Replace with your content -->
              <div class="py-4">
                <div class="border-4 border-dashed border-gray-200 rounded-lg h-96" />
              </div>
              <!-- /End replace -->
              @show
            </div>
          </div>
        </main>
      </div>
  </div>
</body>
</html>