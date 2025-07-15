<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Penjualan</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Transaksi Penjualan</h1>
        <p>Periode: {{ $judulPeriode }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y') }}</td>
                    <td>{{ $transaction->keterangan ?: '-' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($transaction->total_penjualan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="2" style="text-align: right;">Total Pemasukan:</td>
                <td style="text-align: right;">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>