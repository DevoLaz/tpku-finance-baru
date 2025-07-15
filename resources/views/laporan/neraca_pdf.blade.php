<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Neraca</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 2px 0; font-size: 12px; }
        .container { width: 100%; }
        .col { width: 48%; vertical-align: top; display: inline-block; }
        .col-left { margin-right: 2%; }
        .section-title { font-size: 16px; font-weight: bold; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 4px; }
        .text-right { text-align: right; }
        .sub-title { font-weight: bold; }
        .item-row td { border-bottom: 1px solid #eee; }
        .total-row { font-weight: bold; background-color: #f2f2f2; }
        .final-total { font-weight: bold; font-size: 13px; border-top: 2px double #333; padding-top: 8px; margin-top: 5px; }
        .negative { color: #d00; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Posisi Keuangan (Neraca)</h1>
        <p>Per Tanggal {{ \Carbon\Carbon::parse($tanggalLaporan)->format('d F Y') }}</p>
    </div>

    <div class="container">
        <!-- Kolom Aset -->
        <div class="col col-left">
            <div class="section-title">ASET</div>
            <table>
                <tr class="sub-title"><td colspan="2">Aset Lancar</td></tr>
                <tr class="item-row"><td>Kas & Setara Kas</td><td class="text-right">Rp {{ number_format($kas, 0, ',', '.') }}</td></tr>
                <tr class="total-row"><td>Total Aset Lancar</td><td class="text-right">Rp {{ number_format($kas, 0, ',', '.') }}</td></tr>
                
                <tr><td colspan="2" style="padding: 8px;"></td></tr>

                <tr class="sub-title"><td colspan="2">Aset Tetap</td></tr>
                @foreach($asetFisikItems as $item)
                    <tr class="item-row"><td>{{ $item->nama_aset }}</td><td class="text-right">Rp {{ number_format($item->harga_perolehan, 0, ',', '.') }}</td></tr>
                @endforeach
                <tr class="item-row"><td class="negative">Akumulasi Penyusutan</td><td class="text-right negative">(Rp {{ number_format($totalAkumulasiPenyusutan, 0, ',', '.') }})</td></tr>
                <tr class="total-row"><td>Total Aset Tetap (Nilai Buku)</td><td class="text-right">Rp {{ number_format($asetFisikItems->sum('harga_perolehan') - $totalAkumulasiPenyusutan, 0, ',', '.') }}</td></tr>

                <tr><td colspan="2" style="padding: 8px;"></td></tr>

                <tr class="final-total"><td>TOTAL ASET</td><td class="text-right">Rp {{ number_format($totalAset, 0, ',', '.') }}</td></tr>
            </table>
        </div>

        <!-- Kolom Liabilitas & Ekuitas -->
        <div class="col">
            <div class="section-title">LIABILITAS & EKUITAS</div>
            <table>
                <tr class="sub-title"><td colspan="2">Liabilitas</td></tr>
                <tr class="item-row"><td>Utang Usaha</td><td class="text-right">Rp 0</td></tr>
                <tr class="total-row"><td>Total Liabilitas</td><td class="text-right">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</td></tr>

                <tr><td colspan="2" style="padding: 8px;"></td></tr>

                <tr class="sub-title"><td colspan="2">Ekuitas</td></tr>
                <tr class="item-row"><td>Modal Disetor</td><td class="text-right">Rp {{ number_format($modalDisetor, 0, ',', '.') }}</td></tr>
                <tr class="item-row"><td>Laba Ditahan</td><td class="text-right">Rp {{ number_format($labaDitahan, 0, ',', '.') }}</td></tr>
                <tr class="total-row"><td>Total Ekuitas</td><td class="text-right">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</td></tr>

                <tr><td colspan="2" style="padding: 8px;"></td></tr>

                <tr class="final-total"><td>TOTAL LIABILITAS & EKUITAS</td><td class="text-right">Rp {{ number_format($totalLiabilitasEkuitas, 0, ',', '.') }}</td></tr>
            </table>
        </div>
    </div>
</body>
</html>
