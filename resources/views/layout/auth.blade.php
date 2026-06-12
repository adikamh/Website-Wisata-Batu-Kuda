<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Batu Kuda Wisata')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo/favicon.png') }}">
    @stack('styles')
</head>
<body class="@yield('body_class', 'auth-body')">
    @yield('content')

    <x-sweet-alert />
    @stack('scripts')
</body>
</html>
