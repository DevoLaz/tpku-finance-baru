<!DOCTYPE html>
{{-- Menambahkan style untuk memaksa skema warna terang --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="color-scheme: light;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    {{-- Menambahkan kelas text-gray-900 dan bg-[#F9FAF9] untuk warna default --}}
    <body class="font-sans text-gray-900 antialiased bg-[#F9FAF9]">
        {{-- 
            Slot ini akan diisi oleh konten dari halaman lain, 
            seperti forgot-password.blade.php. Tidak ada styling tambahan di sini 
            agar styling dari halaman tersebut bisa mengambil alih sepenuhnya.
        --}}
        {{ $slot }}
    </body>
</html>
