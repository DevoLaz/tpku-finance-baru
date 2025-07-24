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

class LabaRugiExport implements FromArray, WithTitle, WithStyles
{
    protected $data;

    // Properti untuk menyimpan jumlah baris agar styling dinamis
    protected $pItemsCount;
    protected $hppItemsCount;
    protected $gajiItemsCount;
    protected $bItemsCount;
    protected $labaBersih;

    public function __construct(...$data)
    {
        $this->data = $data;
        // Ambil data dan hitung jumlah item di setiap kategori
        list(, $pItems, , $hppItems, $gajiItems, $bItems, , $labaBersih) = $this->data;
        $this->pItemsCount = $pItems->count();
        $this->hppItemsCount = $hppItems->count();
        $this->gajiItemsCount = $gajiItems->count();
        $this->bItemsCount = $bItems->count();
        $this->labaBersih = $labaBersih;
    }

    public function title(): string
    {
        return 'Laporan Laba Rugi';
    }

    public function array(): array
    {
        list($judul, $pItems, $totalP, $hppItems, $gajiItems, $bItems, $penyusutan, $labaBersih) = $this->data;

        $export = [];
        $export[] = ['Laporan Laba Rugi', null];
        $export[] = [$judul, null];
        $export[] = []; // Spasi

        // Bagian Pendapatan
        $export[] = ['Pendapatan', null];
        if ($pItems->isEmpty()) {
            $export[] = ['  Penjualan', 0];
        } else {
            foreach ($pItems as $item) {
                 // Diasumsikan keterangan penjualan bisa dibuat lebih deskriptif
                $export[] = ['  Penjualan tanggal ' . \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y'), $item->total_penjualan];
            }
        }
        $export[] = ['Total Pendapatan', $totalP];
        $export[] = []; // Spasi

        // Bagian HPP & Laba Kotor
        $export[] = ['Beban Pokok Penjualan (HPP)', null];
        $totalHpp = $hppItems->sum('total_harga');
        if ($hppItems->isEmpty()) {
             $export[] = ['  Bahan Baku', 0];
        } else {
            foreach ($hppItems as $item) {
                $export[] = ['  ' . $item->barang->nama, $item->total_harga];
            }
        }
        $export[] = ['Total Beban Pokok Penjualan', $totalHpp];
        $labaKotor = $totalP - $totalHpp;
        $export[] = ['Laba Kotor', $labaKotor];
        $export[] = []; // Spasi
        
        // Bagian Beban Operasional
        $export[] = ['Beban Operasional', null];
        $totalGaji = $gajiItems->sum('gaji_bersih');
        foreach ($gajiItems as $item) {
            $export[] = ['  Beban Gaji: ' . $item->karyawan->nama, $item->gaji_bersih];
        }
        $totalBebanLain = $bItems->sum('jumlah');
        foreach ($bItems as $item) {
            $export[] = ['  ' . $item->nama, $item->jumlah];
        }
        $export[] = ['  Beban Penyusutan', $penyusutan];
        $totalBebanOps = $totalGaji + $totalBebanLain + $penyusutan;
        $export[] = ['Total Beban Operasional', $totalBebanOps];
        $export[] = []; // Spasi
        
        // Hasil Akhir
        $export[] = [$labaBersih >= 0 ? 'Laba Bersih' : 'Rugi Bersih', $labaBersih];

        return $export;
    }

    public function styles(Worksheet $sheet)
    {
        // === PENGATURAN DASAR SHEET ===
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getStyle('B4:B'.$sheet->getHighestRow())->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');
        $sheet->getStyle('A1:B'.$sheet->getHighestRow())->getFont()->setName('Arial');
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // === KOORDINAT BARIS DINAMIS ===
        $rowHeaderPendapatan = 4;
        $rowTotalPendapatan = $rowHeaderPendapatan + ($this->pItemsCount ?: 1) + 1;
        $rowHeaderHpp = $rowTotalPendapatan + 2;
        $rowTotalHpp = $rowHeaderHpp + ($this->hppItemsCount ?: 1) + 1;
        $rowLabaKotor = $rowTotalHpp + 1;
        $rowHeaderBebanOps = $rowLabaKotor + 2;
        $totalBebanOpsItems = $this->gajiItemsCount + $this->bItemsCount + 1; // +1 untuk penyusutan
        $rowTotalBebanOps = $rowHeaderBebanOps + $totalBebanOpsItems + 1;
        $rowLabaBersih = $rowTotalBebanOps + 2;

        // === STYLE JUDUL UTAMA ===
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF173720']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->mergeCells('A2:B2')->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // === DEFINISI STYLE YANG AKAN DIPAKAI BERULANG ===
        $sectionHeaderStyle = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FF000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9EAD3']]
        ];
        $subTotalStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEFEFEF']],
            'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $resultStyle = [
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD0E0E3']],
             'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $finalResultStyle = [
            'font' => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $this->labaBersih >= 0 ? 'FF4CAF50' : 'FFF44336']],
            'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM]]
        ];

        // === MENERAPKAN STYLE KE SETIAP BAGIAN ===
        // 1. Headers Seksi
        $sheet->mergeCells("A{$rowHeaderPendapatan}:B{$rowHeaderPendapatan}")->getStyle("A{$rowHeaderPendapatan}")->applyFromArray($sectionHeaderStyle);
        $sheet->mergeCells("A{$rowHeaderHpp}:B{$rowHeaderHpp}")->getStyle("A{$rowHeaderHpp}")->applyFromArray($sectionHeaderStyle);
        $sheet->mergeCells("A{$rowHeaderBebanOps}:B{$rowHeaderBebanOps}")->getStyle("A{$rowHeaderBebanOps}")->applyFromArray($sectionHeaderStyle);

        // 2. Baris Sub-total
        $sheet->getStyle("A{$rowTotalPendapatan}:B{$rowTotalPendapatan}")->applyFromArray($subTotalStyle);
        $sheet->getStyle("A{$rowTotalHpp}:B{$rowTotalHpp}")->applyFromArray($subTotalStyle);
        $sheet->getStyle("A{$rowTotalBebanOps}:B{$rowTotalBebanOps}")->applyFromArray($subTotalStyle);

        // 3. Baris Hasil (Laba Kotor & Laba Bersih)
        $sheet->getStyle("A{$rowLabaKotor}:B{$rowLabaKotor}")->applyFromArray($resultStyle);
        $sheet->getStyle("A{$rowLabaBersih}:B{$rowLabaBersih}")->applyFromArray($finalResultStyle);
        
        return []; // Return array kosong karena style sudah diterapkan langsung
    }
}
