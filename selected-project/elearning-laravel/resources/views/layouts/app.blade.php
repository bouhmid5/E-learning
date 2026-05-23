<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'E-learning Platform') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <header class="site-header">
            <a href="{{ route('home') }}" class="brand">{{ config('app.name', 'E-learning Platform') }}</a>
        </header>

        <main class="page-shell">
            @yield('content')
        </main>
    </body>
</html>

