<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ArusKasExport implements FromArray, WithTitle, WithStyles
{
    protected $data;
    // Variabel untuk menyimpan hitungan baris, penting untuk styling dinamis
    protected $opMasukCount;
    protected $opKeluarCount;
    protected $investasiCount;
    protected $pendanaanCount;

    public function __construct(...$data)
    {
        $this->data = $data;
        list(, , , $opMasuk, $opKeluar, $investasi, $pendanaan) = $this->data;
        $this->opMasukCount = $opMasuk->count();
        $this->opKeluarCount = $opKeluar->count();
        $this->investasiCount = $investasi->count();
        $this->pendanaanCount = $pendanaan->count();
    }

    public function title(): string
    {
        return 'Laporan Arus Kas';
    }

    public function array(): array
    {
        list($judulPeriode, $saldoAwal, $saldoAkhir, $opMasuk, $opKeluar, $investasi, $pendanaan) = $this->data;
        
        // Mengubah format judul periode agar sesuai target
        $judulPeriodeFormatted = 'Untuk Periode yang Berakhir pada ' . str_replace('Tahun ', 'Tahun ', $judulPeriode);

        $exportData = [];
        
        // == BAGIAN HEADER ==
        $exportData[] = ['Laporan Arus Kas', null];
        $exportData[] = [$judulPeriodeFormatted, null];
        $exportData[] = []; // Spasi

        // == BAGIAN AKTIVITAS OPERASIONAL ==
        $exportData[] = ['Arus Kas dari Aktivitas Operasional', null];
        $exportData[] = []; // Spasi
        
        // Sub-bagian: Penerimaan Kas
        $exportData[] = ['Penerimaan Kas:', null];
        foreach ($opMasuk as $item) {
            $exportData[] = ['  ' . $item->deskripsi, $item->jumlah];
        }
        $totalPenerimaanOperasional = $opMasuk->sum('jumlah');
        $exportData[] = ['Total Penerimaan Kas dari Operasional', $totalPenerimaanOperasional];
        $exportData[] = []; // Spasi

        // Sub-bagian: Pembayaran Kas
        $exportData[] = ['Pembayaran Kas:', null];
        foreach ($opKeluar as $item) {
            // Jumlah sudah negatif, sesuai format (Rp xxx)
            $exportData[] = ['  ' . $item->deskripsi, -$item->jumlah];
        }
        $totalPembayaranOperasional = -$opKeluar->sum('jumlah');
        $exportData[] = ['Total Pembayaran Kas untuk Operasional', $totalPembayaranOperasional];
        $exportData[] = []; // Spasi
        
        $arusKasOperasi = $totalPenerimaanOperasional + $totalPembayaranOperasional;
        $exportData[] = ['Arus Kas Bersih dari Aktivitas Operasional', $arusKasOperasi];
        $exportData[] = []; // Spasi

        // == BAGIAN AKTIVITAS INVESTASI ==
        if ($this->investasiCount > 0) {
            $exportData[] = ['Arus Kas dari Aktivitas Investasi', null];
            $exportData[] = []; // Spasi
            // Di contoh hanya ada pengeluaran, jadi langsung ke pembayaran
            foreach ($investasi as $item) {
                $exportData[] = ['  ' . $item->deskripsi, -$item->jumlah];
            }
            $arusKasInvestasi = -$investasi->sum('jumlah');
            $exportData[] = ['Arus Kas Bersih dari Aktivitas Investasi', $arusKasInvestasi];
            $exportData[] = []; // Spasi
        }
        
        // == BAGIAN AKTIVITAS PENDANAAN (jika ada) ==
        if ($this->pendanaanCount > 0) {
            // ... (logika serupa bisa ditambahkan di sini)
        }
        
        // == BAGIAN SALDO AKHIR ==
        $exportData[] = []; // Spasi
        $exportData[] = ['Saldo Kas Awal Periode', $saldoAwal];
        $exportData[] = ['Saldo Kas Akhir Periode', $saldoAkhir];

        return $exportData;
    }

    public function styles(Worksheet $sheet)
    {
        // === PENGATURAN DASAR & FORMAT ANGKA AKUNTANSI ===
        $sheet->getColumnDimension('A')->setWidth(60);
        $sheet->getColumnDimension('B')->setWidth(25);
        // Format ini akan menampilkan angka negatif dalam kurung: (Rp 1.000.000)
        $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode('_("Rp "* #,##0_);_("Rp "* \(#,##0\);_("Rp "* "-"??_);_(@_)');
        $sheet->getStyle('A:B')->getFont()->setName('Arial')->setSize(10);
        
        // === DEFINISI STYLE UTAMA ===
        $sectionHeaderStyle = ['font' => ['bold' => true, 'size' => 11]];
        $totalRowStyle = ['font' => ['bold' => true], 'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN]]];
        $netTotalRowStyle = ['font' => ['bold' => true], 'borders' => ['top' => ['borderStyle' => Border::BORDER_DOUBLE]]];

        // === STYLING HEADER ===
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A2:B2');

        // === KOORDINAT BARIS DINAMIS ===
        $rowHeaderOperasi = 4;
        $rowTotalPenerimaan = $rowHeaderOperasi + 3 + $this->opMasukCount;
        $rowTotalPembayaran = $rowTotalPenerimaan + 2 + $this->opKeluarCount;
        $rowArusKasOperasi = $rowTotalPembayaran + 2;
        
        $sheet->getStyle("A{$rowHeaderOperasi}")->applyFromArray($sectionHeaderStyle);
        $sheet->getStyle("A{$rowTotalPenerimaan}:B{$rowTotalPenerimaan}")->applyFromArray($totalRowStyle);
        $sheet->getStyle("A{$rowTotalPembayaran}:B{$rowTotalPembayaran}")->applyFromArray($totalRowStyle);
        $sheet->getStyle("A{$rowArusKasOperasi}:B{$rowArusKasOperasi}")->applyFromArray($netTotalRowStyle);
        
        if ($this->investasiCount > 0) {
            $rowHeaderInvestasi = $rowArusKasOperasi + 2;
            $rowArusKasInvestasi = $rowHeaderInvestasi + 2 + $this->investasiCount;
            $sheet->getStyle("A{$rowHeaderInvestasi}")->applyFromArray($sectionHeaderStyle);
            $sheet->getStyle("A{$rowArusKasInvestasi}:B{$rowArusKasInvestasi}")->applyFromArray($netTotalRowStyle);
        }

        // === STYLING BLOK SALDO AKHIR ===
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A".($lastRow - 1).":B".$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A{$lastRow}:B{$lastRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        return [];
    }
}