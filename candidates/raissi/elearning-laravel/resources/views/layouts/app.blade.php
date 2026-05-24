<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Formini</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <header class="site-header">
            @include('layouts.partials.navigation')
        </header>

        <main class="page-shell @yield('page_class')">
            @include('layouts.partials.feedback')
            @yield('content')
        </main>
    </body>
</html>
