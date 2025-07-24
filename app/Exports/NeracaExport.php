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

class NeracaExport implements FromArray, WithTitle, WithStyles
{
    protected $data;
    // Properti untuk styling dinamis
    protected $asetFisikCount;
    protected $modalItemsCount;

    public function __construct(...$data)
    {
        $this->data = $data;
        list(, , $asetFisik, , , , $modalItems, , , ) = $this->data;
        $this->asetFisikCount = $asetFisik->count();
        $this->modalItemsCount = $modalItems->count();
    }

    public function title(): string
    {
        return 'Laporan Neraca';
    }

    public function array(): array
    {
        list($tgl, $kas, $asetFisik, $akumPenyusutan, $totalAset, $totalLiabilitas, $modalItems, $labaDitahan, $totalEkuitas, $totalLiabilitasEkuitas) = $this->data;

        $export = [];
        $export[] = ['Laporan Posisi Keuangan (Neraca)', null];
        $export[] = ['Per Tanggal ' . \Carbon\Carbon::parse($tgl)->format('d F Y'), null];
        $export[] = [];

        $export[] = ['ASET', null, null, 'LIABILITAS DAN EKUITAS', null];
        $export[] = ['Aset Lancar', null, null, 'Liabilitas', null];
        $export[] = ['  Kas dan Setara Kas', $kas, null, '  Utang Usaha', 0];
        $export[] = ['Total Aset Lancar', $kas, null, 'Total Liabilitas', $totalLiabilitas];
        $export[] = [];

        $export[] = ['Aset Tetap', null, null, 'Ekuitas', null];
        
        $asetRows = [];
        foreach ($asetFisik as $aset) {
            $asetRows[] = ['  ' . $aset->nama_aset, $aset->harga_perolehan];
        }
        $asetRows[] = ['  Akumulasi Penyusutan', -$akumPenyusutan];
        $totalAsetTetap = $asetFisik->sum('harga_perolehan') - $akumPenyusutan;
        $asetRows[] = ['Total Aset Tetap', $totalAsetTetap];
        
        $ekuitasRows = [];
        foreach ($modalItems as $item) {
            $ekuitasRows[] = ['  ' . $item->nama_aset, $item->harga_perolehan];
        }
        $ekuitasRows[] = ['  Laba Ditahan', $labaDitahan];
        $ekuitasRows[] = ['Total Ekuitas', $totalEkuitas];
        
        $maxRows = max(count($asetRows), count($ekuitasRows));

        for ($i = 0; $i < $maxRows; $i++) {
            $row = array_merge(
                $asetRows[$i] ?? [null, null],
                [null], // Kolom pemisah
                $ekuitasRows[$i] ?? [null, null]
            );
            $export[] = $row;
        }

        $export[] = [];
        $export[] = ['TOTAL ASET', $totalAset, null, 'TOTAL LIABILITAS DAN EKUITAS', $totalLiabilitasEkuitas];

        return $export;
    }

    public function styles(Worksheet $sheet)
    {
        // === PENGATURAN DASAR SHEET ===
        $sheet->mergeCells('A1:E1')->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A2:E2')->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Atur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(5); // Pemisah
        $sheet->getColumnDimension('D')->setWidth(35);
        $sheet->getColumnDimension('E')->setWidth(20);

        // Format Rupiah
        $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
        $sheet->getStyle('E:E')->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // === STYLE DEFINITIONS ===
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF673AB7']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $subHeaderStyle = ['font' => ['bold' => true, 'size' => 11]];
        $totalStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEFEFEF']],
            'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $grandTotalStyle = [
            'font' => ['bold' => true, 'size' => 13],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD1C4E9']],
            'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM]]
        ];

        // === MENERAPKAN STYLE ===
        // Main Headers
        $sheet->mergeCells('A4:B4')->getStyle('A4')->applyFromArray($headerStyle);
        $sheet->mergeCells('D4:E4')->getStyle('D4')->applyFromArray($headerStyle);

        // Sub Headers
        $sheet->getStyle('A5:B5')->applyFromArray($subHeaderStyle);
        $sheet->getStyle('D5:E5')->applyFromArray($subHeaderStyle);
        $sheet->getStyle('A9:B9')->applyFromArray($subHeaderStyle);
        $sheet->getStyle('D9:E9')->applyFromArray($subHeaderStyle);
        
        // Total Aset Lancar
        $sheet->getStyle('A7:B7')->applyFromArray($totalStyle);
        // Total Liabilitas
        $sheet->getStyle('D7:E7')->applyFromArray($totalStyle);
        
        // Koordinat dinamis untuk total bawah
        $rowTotalAsetTetap = 9 + $this->asetFisikCount + 2;
        $rowTotalEkuitas = 9 + $this->modalItemsCount + 2;
        $rowGrandTotal = max($rowTotalAsetTetap, $rowTotalEkuitas) + 2;

        // Total Aset Tetap & Ekuitas
        $sheet->getStyle("A{$rowTotalAsetTetap}:B{$rowTotalAsetTetap}")->applyFromArray($totalStyle);
        $sheet->getStyle("D{$rowTotalEkuitas}:E{$rowTotalEkuitas}")->applyFromArray($totalStyle);

        // Grand Totals
        $sheet->getStyle("A{$rowGrandTotal}:B{$rowGrandTotal}")->applyFromArray($grandTotalStyle);
        $sheet->getStyle("D{$rowGrandTotal}:E{$rowGrandTotal}")->applyFromArray($grandTotalStyle);

        return [];
    }
}
