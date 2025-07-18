<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arus Kas</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 2px 0; font-size: 12px; }
        .section { margin-bottom: 15px; }
        .section-title { font-size: 14px; font-weight: bold; padding-bottom: 5px; border-bottom: 1px solid #ccc; margin-bottom: 10px; }
        .sub-section-title { font-size: 12px; font-weight: bold; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; text-align: left; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; border-top: 1px solid #333; border-bottom: 1px solid #333; }
        .summary-table { margin-top: 20px; width: 60%; float: right; }
        .summary-table td { border: none; padding: 4px 6px; }
        .final-total { font-size: 13px; font-weight: bold; border-top: 2px double #333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Arus Kas</h1>
        <p>Untuk Periode yang Berakhir pada {{ $judulPeriode }}</p>
    </div>

    <!-- Aktivitas Operasional -->
    <div class="section">
        <div class="section-title">Arus Kas dari Aktivitas Operasional</div>
        <table>
            @if($operasionalMasuk->isNotEmpty())
                <tr><td colspan="2" class="sub-section-title">Penerimaan Kas:</td></tr>
                @foreach($operasionalMasuk as $item)
                <tr>
                    <td>{{ $item->deskripsi }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Penerimaan Kas dari Operasional</td>
                    <td class="text-right">Rp {{ number_format($operasionalMasuk->sum('jumlah'), 0, ',', '.') }}</td>
                </tr>
            @endif

            @if($operasionalKeluar->isNotEmpty())
                <tr><td colspan="2" style="padding-top: 10px;" class="sub-section-title">Pembayaran Kas:</td></tr>
                 @foreach($operasionalKeluar as $item)
                <tr>
                    <td>{{ $item->deskripsi }}</td>
                    <td class="text-right">(Rp {{ number_format($item->jumlah, 0, ',', '.') }})</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Pembayaran Kas untuk Operasional</td>
                    <td class="text-right">(Rp {{ number_format($operasionalKeluar->sum('jumlah'), 0, ',', '.') }})</td>
                </tr>
            @endif

            <tr class="total-row" style="background-color: #f2f2f2;">
                <td>Arus Kas Bersih dari Aktivitas Operasional</td>
                @php $kasBersihOperasional = $operasionalMasuk->sum('jumlah') - $operasionalKeluar->sum('jumlah'); @endphp
                <td class="text-right">Rp {{ number_format($kasBersihOperasional, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Aktivitas Investasi -->
    @if($investasi->isNotEmpty())
    <div class="section">
        <div class="section-title">Arus Kas dari Aktivitas Investasi</div>
        <table>
            @foreach($investasi as $item)
            <tr>
                <td>{{ $item->deskripsi }}</td>
                <td class="text-right">(Rp {{ number_format($item->jumlah, 0, ',', '.') }})</td>
            </tr>
            @endforeach
            <tr class="total-row" style="background-color: #f2f2f2;">
                <td>Arus Kas Bersih dari Aktivitas Investasi</td>
                <td class="text-right">(Rp {{ number_format($investasi->sum('jumlah'), 0, ',', '.') }})</td>
            </tr>
        </table>
    </div>
    @endif

    <!-- Aktivitas Pendanaan -->
    @if($pendanaan->isNotEmpty())
    <div class="section">
        <div class="section-title">Arus Kas dari Aktivitas Pendanaan</div>
        <table>
            @foreach($pendanaan as $item)
            <tr>
                <td>{{ $item->deskripsi }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row" style="background-color: #f2f2f2;">
                <td>Arus Kas Bersih dari Aktivitas Pendanaan</td>
                <td class="text-right">Rp {{ number_format($pendanaan->sum('jumlah'), 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    @endif
    
    <table class="summary-table">
        <tr>
            <td>Saldo Kas Awal Periode</td>
            <td class="text-right">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
        </tr>
        <tr class="final-total">
            <td>Saldo Kas Akhir Periode</td>
            <td class="text-right">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
        </tr>
    </table>

</body>
</html>
