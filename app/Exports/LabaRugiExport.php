<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LabaRugiExport implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct(...$data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Laporan Laba Rugi';
    }

    public function array(): array
    {
        list($judul, $pItems, $totalP, $hppItems, $gajiItems, $bItems, $penyusutan, $labaBersih) = $this->data;

        $export = [];
        $export[] = ['Laporan Laba Rugi', ''];
        $export[] = [$judul, ''];
        $export[] = [];

        $export[] = ['Pendapatan', ''];
        foreach ($pItems as $item) {
            $export[] = ['  ' . $item->keterangan, $item->total_penjualan];
        }
        $export[] = ['Total Pendapatan', $totalP];
        $export[] = [];

        $export[] = ['Beban Pokok Penjualan (HPP)', ''];
        $totalHpp = $hppItems->sum('total_harga');
        foreach ($hppItems as $item) {
            $export[] = ['  ' . $item->barang->nama, $item->total_harga];
        }
        $export[] = ['Total HPP', $totalHpp];
        $labaKotor = $totalP - $totalHpp;
        $export[] = ['Laba Kotor', $labaKotor];
        $export[] = [];
        
        $export[] = ['Beban Operasional', ''];
        $totalGaji = $gajiItems->sum('gaji_bersih');
        foreach ($gajiItems as $item) {
            $export[] = ['  Gaji: ' . $item->karyawan->nama, $item->gaji_bersih];
        }
        $totalBebanLain = $bItems->sum('jumlah');
        foreach ($bItems as $item) {
            $export[] = ['  ' . $item->nama, $item->jumlah];
        }
        $export[] = ['  Beban Penyusutan', $penyusutan];
        $totalBebanOps = $totalGaji + $totalBebanLain + $penyusutan;
        $export[] = ['Total Beban Operasional', $totalBebanOps];
        $export[] = [];
        
        $export[] = ['Laba Bersih', $labaBersih];

        return $export;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:B1')->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->getColumnDimension('B')->setWidth(20);

        // Styling Headers & Totals
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A6:B6')->getFont()->setBold(true);
        $sheet->getStyle('A8')->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 6) . ':B' . ($sheet->getHighestRow() - 6))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 5) . ':B' . ($sheet->getHighestRow() - 5))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 4))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 2) . ':B' . ($sheet->getHighestRow() - 2))->getFont()->setBold(true);
        $sheet->getStyle('A' . $sheet->getHighestRow() . ':B' . $sheet->getHighestRow())->getFont()->setBold(true)->setSize(14);
    }
}
