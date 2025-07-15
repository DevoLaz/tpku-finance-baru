<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Beban Operasional</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 2px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 7px; text-align: left; }
        th { background-color: #f2f2f2; font-size: 12px; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background-color: #f2f2f2; border-top: 2px solid #333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Beban Operasional</h1>
        <p>
            Periode: 
            @if($dari || $sampai)
                {{ $dari ? \Carbon\Carbon::parse($dari)->format('d M Y') : 'Awal' }} - 
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d M Y') : 'Akhir' }}
            @else
                Semua Data
            @endif
        </p>
        <p>Kategori: {{ $kategoriNama }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Beban</th>
                <th>Kategori</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bebans as $beban)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($beban->tanggal)->format('d M Y') }}</td>
                    <td>{{ $beban->nama }}</td>
                    <td>{{ $beban->kategori->nama_kategori ?? 'N/A' }}</td>
                    <td class="text-right">Rp {{ number_format($beban->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data beban untuk periode/kategori yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Total Beban</td>
                <td class="text-right">Rp {{ number_format($totalBeban, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
