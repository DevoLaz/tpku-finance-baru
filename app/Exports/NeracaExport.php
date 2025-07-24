<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NeracaExport implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct(...$data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Laporan Neraca';
    }

    public function array(): array
    {
        list($tgl, $kas, $asetFisik, $akumPenyusutan, $totalAset, $totalLiabilitas, $modalItems, $labaDitahan, $totalEkuitas, $totalLiabilitasEkuitas) = $this->data;

        $export = [];
        $export[] = ['Laporan Posisi Keuangan (Neraca)', ''];
        $export[] = ['Per Tanggal ' . \Carbon\Carbon::parse($tgl)->format('d F Y'), ''];
        $export[] = [];

        $export[] = ['ASET', ''];
        $export[] = ['Aset Lancar', ''];
        $export[] = ['  Kas dan Setara Kas', $kas];
        $export[] = ['Total Aset Lancar', $kas];
        $export[] = [];

        $export[] = ['Aset Tetap', ''];
        foreach ($asetFisik as $aset) {
            $export[] = ['  ' . $aset->nama_aset, $aset->harga_perolehan];
        }
        $export[] = ['  Akumulasi Penyusutan', -$akumPenyusutan];
        $totalAsetTetap = $asetFisik->sum('harga_perolehan') - $akumPenyusutan;
        $export[] = ['Total Aset Tetap', $totalAsetTetap];
        $export[] = [];
        
        $export[] = ['TOTAL ASET', $totalAset];
        $export[] = [];

        $export[] = ['LIABILITAS DAN EKUITAS', ''];
        $export[] = ['Liabilitas', ''];
        $export[] = ['Total Liabilitas', $totalLiabilitas];
        $export[] = [];

        $export[] = ['Ekuitas', ''];
        $totalModal = $modalItems->sum('harga_perolehan');
        foreach ($modalItems as $item) {
            $export[] = ['  ' . $item->nama_aset, $item->harga_perolehan];
        }
        $export[] = ['  Laba Ditahan', $labaDitahan];
        $export[] = ['Total Ekuitas', $totalEkuitas];
        $export[] = [];

        $export[] = ['TOTAL LIABILITAS DAN EKUITAS', $totalLiabilitasEkuitas];

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
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('A7:B7')->getFont()->setBold(true);
        $sheet->getStyle('A9')->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 11) . ':B' . ($sheet->getHighestRow() - 11))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 9) . ':B' . ($sheet->getHighestRow() - 9))->getFont()->setBold(true)->setSize(14);
        
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 7))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 6))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 5) . ':B' . ($sheet->getHighestRow() - 5))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 3))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($sheet->getHighestRow() - 1) . ':B' . ($sheet->getHighestRow() - 1))->getFont()->setBold(true);
        $sheet->getStyle('A' . $sheet->getHighestRow() . ':B' . $sheet->getHighestRow())->getFont()->setBold(true)->setSize(14);
    }
}
