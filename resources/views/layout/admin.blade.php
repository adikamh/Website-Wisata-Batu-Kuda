<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1a3c28;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #2d6a4f;
            border-radius: 10px;
        }

        .transition-smooth {
            transition: all 0.2s ease-in-out;
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body_class', 'bg-[#f8f4ec] font-sans antialiased')">
    @yield('content')

    <x-sweet-alert :flash="false" :validation="false" />
    @stack('scripts')
</body>
</html>
