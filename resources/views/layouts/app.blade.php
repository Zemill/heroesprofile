<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @include('scripts.header')
    @include('scripts.footer')
  </head>
  <body class="darkTheme">
      <div id="app">
        @include('nav.primary')
          <main class="container-fluid ">
              @yield('content')
          </main>
      </div>
      @include('layouts.footer')
      <script src="{{ asset('js/app.js') }}"></script>
  </body>
  @yield('scripts')
</html>
