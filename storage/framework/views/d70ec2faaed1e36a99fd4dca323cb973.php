<aside class="flex flex-col w-20 hover:w-64 bg-[#173720] text-white shadow-md border-r border-[#2a5132] fixed top-0 left-0 h-screen transition-all duration-300 overflow-hidden z-50 group/sidebar">
    
    <!-- Header Sidebar -->
    <div class="flex items-center gap-3 p-6 border-b border-[#2a5132] h-[69px] shrink-0">
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3">
            <span class="text-lg font-bold hidden group-hover/sidebar:inline-block transition-all duration-300 whitespace-nowrap">TPKU Finance</span>
        </a>
    </div>

    <!-- Area Navigasi (Bisa di-scroll) -->
    <div class="flex-1 overflow-y-auto no-scrollbar">
        <nav class="flex flex-col px-4 py-6 space-y-1 text-base">
            
            
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('dashboard') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="home" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Dashboard</span>
            </a>

            <!-- Grup Input Data -->
            <div class="px-2 pt-4 pb-1">
                <span class="text-xs font-bold text-gray-400 hidden group-hover/sidebar:inline-block">INPUT DATA</span>
            </div>
            <a href="<?php echo e(route('transaksi.index')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('transaksi.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="receipt" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Transaksi Penjualan</span>
            </a>
            <a href="<?php echo e(route('pengadaan.index')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('pengadaan.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="shopping-basket" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Pengadaan Bahan</span>
            </a>
            <a href="<?php echo e(route('beban.index')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('beban.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="shield-alert" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Beban Operasional</span>
            </a>
            <a href="<?php echo e(route('laporan.penggajian.index')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('laporan.penggajian.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="wallet" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Penggajian</span>
            </a>

            <!-- Grup Data Master -->
            <div class="px-2 pt-4 pb-1">
                <span class="text-xs font-bold text-gray-400 hidden group-hover/sidebar:inline-block">DATA MASTER</span>
            </div>
            <!-- Tambahan Master Data -->
            <a href="<?php echo e(route('master.index')); ?>"
               class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('master.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="layers" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Master Data</span>
            </a>
            <a href="<?php echo e(route('aset-tetap.index')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('aset-tetap.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="building" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Aset & Modal</span>
            </a>
            <a href="<?php echo e(route('karyawan.index')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('karyawan.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Manajemen Karyawan</span>
            </a>

            <!-- Grup Laporan -->
            <div class="px-2 pt-4 pb-1">
                <span class="text-xs font-bold text-gray-400 hidden group-hover/sidebar:inline-block">LAPORAN KEUANGAN</span>
            </div>
            <a href="<?php echo e(route('laporan.arus_kas')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('laporan.arus_kas') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="book-open" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Laporan Arus Kas</span>
            </a>
            <a href="<?php echo e(route('laporan.laba_rugi')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('laporan.laba_rugi') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="bar-chart-3" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Laporan Laba Rugi</span>
            </a>
            <a href="<?php echo e(route('laporan.neraca')); ?>" class="flex items-center gap-3 p-2 rounded-md transition <?php echo e(request()->routeIs('laporan.neraca') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]'); ?>">
                <i data-lucide="scale" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Laporan Neraca</span>
            </a>
        </nav>
    </div>

    <!-- Logout -->
    <div class="p-4 border-t border-[#2a5132] shrink-0">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-3 w-full hover:bg-red-600 p-2 rounded-md text-white transition">
                <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap font-semibold">Logout</span>
            </button>
        </form>
    </div>
</aside>
<?php /**PATH C:\tpku-finance-baru\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>