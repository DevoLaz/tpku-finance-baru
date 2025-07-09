// devolaz/tpku-finance-baru/tpku-finance-baru-73477388ecef21eb63a4f0a3d263b58c43c6f7ce/resources/js/app.js
import './bootstrap';

// Pastikan Alpine.js diimpor dan dimulai
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
console.log('Alpine.js started!'); // Tambahkan ini untuk debugging

document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons(); // Inisialisasi Lucide Icons
    console.log('DOM Content Loaded. Lucide icons created.'); // Tambahkan ini

    const loadingScreen = document.getElementById('loadingScreen');
    if (loadingScreen) {
        // Sembunyikan loading screen saat halaman siap
        loadingScreen.classList.add('hidden');
        console.log('Loading screen hidden.'); // Tambahkan ini
    }
});

window.addEventListener('beforeunload', function () {
    const loadingScreen = document.getElementById('loadingScreen');
    if (loadingScreen) {
        loadingScreen.classList.remove('hidden');
        console.log('Loading screen shown (beforeunload).'); // Tambahkan ini
    }
});