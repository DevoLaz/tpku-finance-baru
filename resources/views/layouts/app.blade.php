<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & CSS dari Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Flatpickr (Date Picker) dari layout lamamu -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <!-- Lucide Icons dari layout lamamu -->
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#F9FAF9]">

            <!-- Memasukkan Sidebar Kerenmu -->
            @include('layouts.sidebar')

            <!-- Konten Utama Halaman -->
            <main class="flex-1 transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
                {{ $slot }}
            </main>
        </div>

        <!-- Stack script tambahan dari layout lamamu -->
        @stack('scripts')
    </body>
</html>