<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(34, 197, 94, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .glass-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(34, 197, 94, 0.15);
            border-color: rgba(34, 197, 94, 0.3);
        }
        
        .metric-card {
            position: relative;
            overflow: hidden;
        }
        
        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #22c55e, #16a34a, #15803d);
            transform: scaleX(0);
            transition: transform 0.6s ease;
        }
        
        .metric-card:hover::before {
            transform: scaleX(1);
        }
        
        .chart-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 12px 40px rgba(34, 197, 94, 0.08);
            transition: all 0.3s ease;
        }
        
        .chart-container:hover {
            box-shadow: 0 20px 60px rgba(34, 197, 94, 0.12);
        }
        
        .floating-icon {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .stats-glow {
            text-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }
        
        .badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        
        .badge:hover::before {
            left: 100%;
        }
        
        .badge-masuk { 
            background: linear-gradient(135deg, #dcfce7, #bbf7d0); 
            color: #15803d; 
            border: 1px solid #86efac;
        }
        
        .badge-keluar { 
            background: linear-gradient(135deg, #fee2e2, #fecaca); 
            color: #dc2626; 
            border: 1px solid #f87171;
        }
        
        .transaction-row {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .transaction-row:hover {
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.05), rgba(34, 197, 94, 0.02));
            border-left-color: #22c55e;
            transform: translateX(4px);
        }
        
        .welcome-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #334155 50%, #22c55e 100%);
            position: relative;
            overflow: hidden;
        }
        
        .welcome-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .trend-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }
        
        .trend-up {
            background: rgba(34, 197, 94, 0.1);
            color: #15803d;
        }
        
        .trend-down {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }
        
        .animated-counter {
            animation: countUp 2s ease-out;
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .quick-action-btn {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .quick-action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }
        
        .quick-action-btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .insight-card {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.05), rgba(16, 185, 129, 0.05));
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
    </style>
    @endpush

    <div class="p-4 md:p-6 lg:p-8 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 min-h-screen">
        {{-- HEADER SECTION --}}
        <div class="welcome-gradient rounded-3xl p-6 md:p-8 mb-8 text-white shadow-2xl relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800">
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="flex-1 px-0">
    @php
        $now = now('Asia/Jakarta');
        $hour = (int) $now->format('H');
        $greeting = match(true) {
            $hour >= 5 && $hour < 12 => 'Selamat Pagi',
            $hour >= 12 && $hour < 15 => 'Selamat Siang', 
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam'
        };
    @endphp

    <div class="flex items-start mb-2">
        <h1 class="text-2xl md:text-4xl font-bold text-left">
            {{ $greeting }}, {{ Auth::user()->name }}!
        </h1>
    </div>

    <p class="text-green-100 text-lg mb-4 text-left">
        Dashboard analitik keuangan terkini untuk bisnis Anda
    </p>

    <div class="flex items-center text-sm text-green-200 text-left space-x-1">
        <span class="inline-block w-2 h-2 rounded-full bg-green-400"></span>
        <span>Terakhir diperbarui: {{ now('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
    </div>
</div>


                    
                    {{-- Quick Actions --}}
                    <div class="flex flex-wrap gap-3">
                        <!-- <button class="quick-action-btn text-white px-6 py-3 rounded-xl font-semibold relative z-10">
                            <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                            Transaksi Baru
                        </button> -->
                        <!-- <button class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/30 transition-all">
                            <i data-lucide="download" class="w-5 h-5 inline mr-2"></i>
                            Export Data
                        </button> -->
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN METRICS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            {{-- Total Kas & Bank --}}
            <div class="glass-card metric-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Kas & Bank</p>
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-bold text-gray-900 animated-counter stats-glow">
                                Rp {{ number_format($totalKas, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
<div class="p-4 rounded-2xl text-white" style="background: linear-gradient(135deg, #818cf8, #4338ca);">
                        <i data-lucide="wallet" class="w-8 h-8"></i>
                    </div>
                </div>
            </div>

            {{-- Nilai Aset --}}
            <div class="glass-card metric-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Nilai Aset Tetap</p>
                        <p class="text-3xl font-bold text-blue-900 animated-counter">
                            Rp {{ number_format($totalAset, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-4 rounded-2xl text-white" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                        <i data-lucide="building-2" class="w-8 h-8"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                </div>
            </div>

            {{-- Laba Rugi --}}
            <div class="glass-card metric-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Laba/Rugi Bulan Ini</p>
                        <p class="text-3xl font-bold animated-counter {{ $labaRugiBulanIni >= 0 ? 'text-green-900' : 'text-red-600' }}">
                            Rp {{ number_format($labaRugiBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-4 rounded-2xl text-white" style="background: linear-gradient(135deg, {{ $labaRugiBulanIni >= 0 ? '#10b981, #059669' : '#ef4444, #dc2626' }});">
                        <i data-lucide="{{ $labaRugiBulanIni >= 0 ? 'trending-up' : 'trending-down' }}" class="w-8 h-8"></i>
                    </div>
                </div>
            </div>

            {{-- Karyawan --}}
            <div class="glass-card metric-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Karyawan Aktif</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-bold text-orange-900 animated-counter">{{ $totalKaryawan }}</p>
                            <span class="text-lg text-gray-500">Orang</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl text-white" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                        <i data-lucide="users" class="w-8 h-8"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                </div>
            </div>
        </div>

        {{-- INSIGHTS CARDS --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="insight-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i data-lucide="target" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">Target Bulanan</h4>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Progress: 78%</p>
                        <p class="text-lg font-bold text-green-700">Rp 450M / Rp 580M</p>
                    </div>
                    <div class="w-16 h-16">
                        <svg class="progress-ring w-16 h-16">
                            <circle cx="32" cy="32" r="28" stroke="#e5e7eb" stroke-width="4" fill="transparent"/>
                            <circle cx="32" cy="32" r="28" stroke="#22c55e" stroke-width="4" fill="transparent"
                                    stroke-dasharray="175.93" stroke-dashoffset="38.5" class="progress-ring-circle"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="insight-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i data-lucide="zap" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">Efisiensi Operasional</h4>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biaya vs Pendapatan</span>
                        <span class="font-semibold text-blue-700">85%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>

            <div class="insight-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i data-lucide="activity" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">Aktivitas Hari Ini</h4>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-gray-600">12 Transaksi masuk</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span class="text-gray-600">8 Transaksi keluar</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHARTS SECTION --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
            {{-- Arus Kas Chart --}}
            <div class="xl:col-span-2 chart-container rounded-3xl p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">Arus Kas Bulanan</h3>
                        <p class="text-sm text-gray-500">Periode: {{ $arusKasChart[0]['bulan'] ?? '-' }} - {{ $arusKasChart[count($arusKasChart)-1]['bulan'] ?? '-' }}</p>
                    </div>
                    <div class="flex gap-4 mt-4 sm:mt-0">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-600">
                                Masuk: Rp {{ number_format(array_sum(array_column($arusKasChart->toArray(), 'masuk')), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-600">
                                Keluar: Rp {{ number_format(array_sum(array_column($arusKasChart->toArray(), 'keluar')), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div style="height: 320px;">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>

            {{-- Expense Composition --}}
            <div class="chart-container rounded-3xl p-6">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Komposisi Pengeluaran</h3>
                    <p class="text-sm text-gray-500">Bulan berjalan</p>
                </div>
                
                <div class="space-y-3 mb-6">
                    @foreach($pengeluaranChart['labels'] as $i => $label)
                        <div class="flex items-center justify-between p-3 rounded-xl" style="background: {{ ['#ef444410', '#f9731610', '#eab30810'][$i % 3] }}">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full" style="background: {{ ['#ef4444', '#f97316', '#eab308'][$i % 3] }}"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            </div>
                            <span class="text-sm font-bold" style="color: {{ ['#ef4444', '#f97316', '#eab308'][$i % 3] }}">
                                Rp {{ number_format($pengeluaranChart['data'][$i], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
                
                <div style="height: 200px;">
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
        </div>

        {{-- RECENT TRANSACTIONS --}}
        <div class="glass-card rounded-3xl p-6 shadow-lg">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Transaksi Terbaru</h3>
                    <p class="text-sm text-gray-500">5 aktivitas keuangan terakhir</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="p-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="p-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="p-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="p-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksiTerbaru as $transaksi)
                        <tr class="transaction-row">
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal)->isoFormat('D MMM YYYY') }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal)->isoFormat('HH:mm') }} WIB
                                    </span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-lg {{ $transaksi->tipe == 'masuk' ? 'bg-green-100' : 'bg-red-100' }}">
                                        <i data-lucide="{{ $transaksi->tipe == 'masuk' ? 'arrow-down-left' : 'arrow-up-right' }}" 
                                           class="w-4 h-4 {{ $transaksi->tipe == 'masuk' ? 'text-green-600' : 'text-red-600' }}"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->deskripsi }}</p>
                                        <p class="text-xs text-gray-500">ID: #{{ substr(md5($transaksi->id), 0, 8) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="badge {{ $transaksi->tipe == 'masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                                    <i data-lucide="{{ $transaksi->tipe == 'masuk' ? 'plus' : 'minus' }}" class="w-3 h-3"></i>
                                    {{ ucfirst($transaksi->tipe) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <span class="text-lg font-bold {{ $transaksi->tipe == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaksi->tipe == 'masuk' ? '+' : '-' }} Rp {{ number_format(abs($transaksi->jumlah), 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                               
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-gray-100 rounded-full">
                                        <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                                        <p class="text-sm text-gray-400">Mulai tambahkan transaksi pertama Anda</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const arusKasData = @json($arusKasChart);
                const pengeluaranData = @json($pengeluaranChart);

                // Enhanced Cash Flow Chart
                const cashFlowCtx = document.getElementById('cashFlowChart');
                if (cashFlowCtx && arusKasData) {
                    new Chart(cashFlowCtx, {
                        type: 'bar',
                        data: {
                            labels: arusKasData.map(d => d.bulan),
                            datasets: [
                                {
                                    label: 'Kas Masuk',
                                    data: arusKasData.map(d => Math.abs(d.masuk)),
                                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                    borderColor: 'rgba(34, 197, 94, 1)',
                                    borderWidth: 2,
                                    borderRadius: 12,
                                    borderSkipped: false,
                                },
                                {
                                    label: 'Kas Keluar',
                                    data: arusKasData.map(d => Math.abs(d.keluar)),
                                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                    borderColor: 'rgba(239, 68, 68, 1)',
                                    borderWidth: 2,
                                    borderRadius: 12,
                                    borderSkipped: false,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: {
                                            family: 'Inter',
                                            size: 14,
                                            weight: '600'
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: 'white',
                                    bodyColor: 'white',
                                    borderColor: '#22c55e',
                                    borderWidth: 1,
                                    cornerRadius: 12,
                                    padding: 12,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                        drawBorder: false,
                                    },
                                    ticks: {
                                        font: {
                                            family: 'Inter',
                                            size: 12
                                        },
                                        callback: function(value) {
                                            return 'Rp ' + (value / 1000000) + 'M';
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                    ticks: {
                                        font: {
                                            family: 'Inter',
                                            size: 12,
                                            weight: '500'
                                        }
                                    }
                                }
                            },
                            elements: {
                                bar: {
                                    borderRadius: 8,
                                }
                            }
                        }
                    });
                }

                // Enhanced Expense Doughnut Chart
                const expenseCtx = document.getElementById('expenseChart');
                if (expenseCtx && pengeluaranData) {
                    new Chart(expenseCtx, {
                        type: 'doughnut',
                        data: {
                            labels: pengeluaranData.labels,
                            datasets: [{
                                label: 'Pengeluaran',
                                data: pengeluaranData.data,
                                backgroundColor: [
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(249, 115, 22, 0.8)',
                                    'rgba(234, 179, 8, 0.8)',
                                    'rgba(168, 85, 247, 0.8)',
                                    'rgba(59, 130, 246, 0.8)'
                                ],
                                borderColor: [
                                    'rgba(239, 68, 68, 1)',
                                    'rgba(249, 115, 22, 1)',
                                    'rgba(234, 179, 8, 1)',
                                    'rgba(168, 85, 247, 1)',
                                    'rgba(59, 130, 246, 1)'
                                ],
                                borderWidth: 3,
                                hoverOffset: 15,
                                cutout: '65%'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: 'white',
                                    bodyColor: 'white',
                                    borderColor: '#22c55e',
                                    borderWidth: 1,
                                    cornerRadius: 12,
                                    padding: 12,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) label += ': ';
                                            label += 'Rp ' + context.parsed.toLocaleString('id-ID');
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            label += ' (' + percentage + '%)';
                                            return label;
                                        }
                                    }
                                }
                            },
                            elements: {
                                arc: {
                                    borderRadius: 8
                                }
                            }
                        }
                    });
                }

                // Add some interactive animations
                const metricCards = document.querySelectorAll('.metric-card');
                metricCards.forEach((card, index) => {
                    card.style.animationDelay = `${index * 0.1}s`;
                    card.classList.add('animate-fade-in-up');
                });

                // Animate counters
                const animateCounters = () => {
                    const counters = document.querySelectorAll('.animated-counter');
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.style.animationPlayState = 'running';
                            }
                        });
                    });
                    
                    counters.forEach(counter => {
                        observer.observe(counter);
                    });
                };

                animateCounters();
            });

            // Add CSS animation class
            const style = document.createElement('style');
            style.textContent = `
                .animate-fade-in-up {
                    animation: fadeInUp 0.6s ease-out forwards;
                    opacity: 0;
                    transform: translateY(20px);
                }
                
                @keyframes fadeInUp {
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            `;
            document.head.appendChild(style);
        </script>
    @endpush
</x-app-layout>