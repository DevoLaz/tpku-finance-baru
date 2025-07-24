<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class PengadaansExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    protected $pengadaansByInvoice;
    protected $totalPengeluaran;

    public function __construct($pengadaansByInvoice, $totalPengeluaran)
    {
        $this->pengadaansByInvoice = $pengadaansByInvoice;
        $this->totalPengeluaran = $totalPengeluaran;
    }

    public function array(): array
    {
        $exportData = [];

        foreach ($this->pengadaansByInvoice as $invoiceNumber => $items) {
            $firstItem = $items->first();

            // Baris Utama Invoice
            $exportData[] = [
                'tanggal'    => Carbon::parse($firstItem->tanggal_pembelian)->format('d-m-Y'),
                'invoice'    => $invoiceNumber,
                'supplier'   => $firstItem->supplier->nama_supplier ?? 'N/A',
                'harga'      => '',
                'total'      => $items->sum('total_harga'),
            ];

            // Sub-header untuk detail
            $exportData[] = ['', '   Nama Barang', 'Jumlah', 'Harga Beli', 'Subtotal'];

            // Setiap item dalam invoice
            foreach ($items as $item) {
                $exportData[] = [
                    '',
                    '   ' . ($item->barang->nama ?? 'N/A'),
                    $item->jumlah_masuk ?? 0,
                    $item->harga_beli ?? 0,
                    $item->total_harga ?? 0,
                ];
            }

            // Baris kosong sebagai pemisah
            $exportData[] = ['', '', '', '', ''];
        }

        // Baris Total Keseluruhan
        if ($this->pengadaansByInvoice->isNotEmpty()) {
            $exportData[] = ['', '', '', '', '']; // Spasi
            $exportData[] = [
                'TOTAL PENGELUARAN PERIODE INI',
                '', '', '',
                $this->totalPengeluaran,
            ];
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Invoice / Nama Barang',
            'Supplier / Jumlah',
            'Harga Beli',
            'Total',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF173720');
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');
        
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $currentRow = 1;

                foreach ($this->pengadaansByInvoice as $invoiceNumber => $items) {
                    $startRow = $currentRow + 1;
                    
                    // Style baris utama invoice
                    $sheet->getStyle("A{$startRow}:E{$startRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$startRow}:E{$startRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
                    $sheet->getStyle("E{$startRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                    
                    $currentRow++;

                    $itemCount = $items->count();

                    if ($itemCount > 0) {
                        // Style sub-header
                        $subHeaderRow = $currentRow + 1;
                        $sheet->getStyle("B{$subHeaderRow}:E{$subHeaderRow}")->getFont()->setBold(true);
                        $currentRow++;

                        // Style untuk setiap baris item
                        for ($i = 0; $i < $itemCount; $i++) {
                            $itemRow = $currentRow + 1;
                            $sheet->getStyle("D{$itemRow}:E{$itemRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                            $currentRow++;
                        }
                    }
                    
                    $endRow = $currentRow;
                    $sheet->getStyle("A{$startRow}:E{$endRow}")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFD1D5DB');

                    $currentRow++; // Spasi
                }

                if ($this->pengadaansByInvoice->isNotEmpty()) {
                    $totalRow = $currentRow + 2;
                    $sheet->mergeCells("A{$totalRow}:D{$totalRow}");
                    
                    $styleArray = [
                        'font' => ['color' => ['argb' => 'FFFFFFFF'], 'bold' => true, 'size' => 12],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF173720']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ];
                    
                    $sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray($styleArray);
                    $sheet->getStyle("E{$totalRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                }
            },
        ];
    }
}