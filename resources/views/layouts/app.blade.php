<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'DCRMS') }} - @yield('title', 'Data Center Resource Management')</title>

    <!-- Meta Tags -->
    <meta name="description" content="Manage your data center resources efficiently with DCRMS.">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}?v=1">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ui.css') }}">
    @yield('styles')
</head>
<body>
    <div id="app">
        <x-navbar />

        <main>
            @yield('content')
        </main>

        <x-footer />
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/global.js') }}"></script>
    @yield('scripts')
</body>
</html>
