<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 2px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; }
        .text-right { text-align: right; }
        .section-title { font-size: 14px; font-weight: bold; padding-top: 15px; }
        .item-row td { border-bottom: 1px solid #eee; }
        .total-row { font-weight: bold; border-top: 1px solid #333; }
        .final-result { font-size: 16px; font-weight: bold; padding-top: 10px; border-top: 2px double #333; }
        .negative { color: #d00; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Laba Rugi</h1>
        <p>{{ $judulPeriode }}</p>
    </div>

    <table>
        <!-- Pendapatan -->
        <tr>
            <td colspan="2" class="section-title">Pendapatan</td>
        </tr>
        @forelse ($pendapatanItems as $item)
            <tr class="item-row">
                <td>Penjualan</td>
                <td class="text-right">Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr class="item-row">
                <td>Penjualan</td>
                <td class="text-right">Rp 0</td>
            </tr>
        @endforelse
        <tr class="total-row">
            <td>Total Pendapatan</td>
            <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
        </tr>

        <!-- Beban-Beban -->
        <tr>
            <td colspan="2" class="section-title">Beban-Beban</td>
        </tr>
        @forelse ($pengeluaran as $item)
            <tr class="item-row">
                <td>{{ $item['keterangan'] }}</td>
                <td class="text-right negative">(Rp {{ number_format($item['jumlah'], 0, ',', '.') }})</td>
            </tr>
        @empty
            <tr class="item-row">
                <td>Tidak ada beban</td>
                <td class="text-right negative">(Rp 0)</td>
            </tr>
        @endforelse
        <tr class="total-row">
            <td>Total Beban</td>
            <td class="text-right negative">(Rp {{ number_format($totalPengeluaran, 0, ',', '.') }})</td>
        </tr>

        <!-- Laba/Rugi Bersih -->
        @php $isProfit = $labaBersih >= 0; @endphp
        <tr class="final-result">
            <td>{{ $isProfit ? 'Laba Bersih' : 'Rugi Bersih' }}</td>
            <td class="text-right {{ !$isProfit ? 'negative' : '' }}">
                Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
            </td>
        </tr>
    </table>
</body>
</html>
