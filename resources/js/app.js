import './bootstrap';

document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons(); // Inisialisasi Lucide Icons

    const loadingScreen = document.getElementById('loadingScreen');
    if (loadingScreen) {
        // Sembunyikan loading screen saat halaman siap
        loadingScreen.classList.add('hidden');
    }
});

window.addEventListener('beforeunload', function () {
    const loadingScreen = document.getElementById('loadingScreen');
    if (loadingScreen) {
        loadingScreen.classList.remove('hidden');
    }
});

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
