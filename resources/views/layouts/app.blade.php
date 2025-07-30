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
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
<!-- Modal untuk menampilkan Bukti -->
<div id="buktiModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center" onclick="closeModal()">
    <div class="bg-white p-4 rounded-lg max-w-3xl max-h-[90vh] overflow-auto" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-semibold text-lg">Bukti Transaksi</h2>
            <button onclick="closeModal()" class="text-black text-2xl">&times;</button>
        </div>
        <img id="buktiImage" src="" alt="Bukti Transaksi" class="w-full rounded-md">
    </div>
</div>

@stack('scripts')
<script>
    const modal = document.getElementById('buktiModal');
    const modalImage = document.getElementById('buktiImage');

    function showModal(imageUrl) {
        if (imageUrl) {
            modalImage.src = imageUrl;
            modal.classList.remove('hidden');
        }
    }

    function closeModal() {
        modal.classList.add('hidden');
        modalImage.src = ''; 
    }

    // Event listener untuk elemen (tombol atau gambar) yang memiliki atribut data-img-url
    document.addEventListener('click', function (event) {
        const triggerElement = event.target.closest('[data-img-url]');
        if (triggerElement) {
            showModal(triggerElement.dataset.imgUrl);
        }
    });
</script>
    </body>
</html>