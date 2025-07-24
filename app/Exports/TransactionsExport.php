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
use PhpOffice\PhpSpreadsheet\Style\Alignment; // Import Alignment
use Carbon\Carbon;

class TransactionsExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    protected $transactions;
    protected $totalPemasukan;

    public function __construct($transactions, $totalPemasukan)
    {
        $this->transactions = $transactions;
        $this->totalPemasukan = $totalPemasukan;
    }

    public function array(): array
    {
        $exportData = [];

        foreach ($this->transactions as $transaction) {
            $exportData[] = [
                'tanggal'    => Carbon::parse($transaction->tanggal_transaksi)->format('d-m-Y'),
                'keterangan' => $transaction->keterangan,
                'qty'        => '',
                'harga'      => '',
                'total'      => $transaction->total_penjualan,
            ];

            $items = json_decode($transaction->items_detail, true);
            if (is_string($items)) {
                $items = json_decode($items, true);
            }

            if (is_array($items) && !empty($items) && isset($items[0])) {
                $exportData[] = ['', '   Nama Barang', 'Qty', 'Harga Satuan', 'Subtotal'];
                foreach ($items as $item) {
                    $item = (array) $item;
                    $exportData[] = [
                        '', '   ' . ($item['name'] ?? 'N/A'),
                        $item['qty'] ?? 'N/A',
                        $item['price'] ?? 0,
                        $item['subtotal'] ?? 0,
                    ];
                }
            }
            $exportData[] = ['', '', '', '', ''];
        }

        if ($this->transactions->isNotEmpty()) {
            $exportData[] = ['', '', '', '', ''];
            // --- UBAH BAGIAN INI ---
            $exportData[] = [
                'TOTAL PEMASUKAN PERIODE INI', // Label dipindah ke Kolom A
                '', // Kolom B dikosongkan
                '', // Kolom C dikosongkan
                '', // Kolom D dikosongkan
                $this->totalPemasukan,
            ];
            // --- SELESAI PERUBAHAN ---
        }

        return $exportData;
    }

    public function headings(): array
    {
        return ['Tanggal Transaksi', 'Keterangan', 'Qty', 'Harga Satuan', 'Total'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF173720');
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');
        
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $currentRow = 1;

                foreach ($this->transactions as $transaction) {
                    $startRow = $currentRow + 1;
                    $sheet->getStyle("A{$startRow}:E{$startRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$startRow}:E{$startRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
                    $sheet->getStyle("E{$startRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                    $currentRow++;
                    $items = json_decode($transaction->items_detail, true);
                    if (is_string($items)) $items = json_decode($items, true);
                    $itemCount = (is_array($items) && isset($items[0])) ? count($items) : 0;
                    if ($itemCount > 0) {
                        $subHeaderRow = $currentRow + 1;
                        $sheet->getStyle("B{$subHeaderRow}:E{$subHeaderRow}")->getFont()->setBold(true);
                        $sheet->getStyle("B{$subHeaderRow}:E{$subHeaderRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');
                        $currentRow++;
                        for ($i = 0; $i < $itemCount; $i++) {
                            $itemRow = $currentRow + 1;
                            $sheet->getStyle("D{$itemRow}:E{$itemRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                            $currentRow++;
                        }
                    }
                    $endRow = $currentRow;
                    $sheet->getStyle("A{$startRow}:E{$endRow}")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFD1D5DB');
                    $currentRow++;
                }

                // --- UBAH BAGIAN INI ---
                if ($this->transactions->isNotEmpty()) {
                    $totalRow = $currentRow + 2;
                    // Gabungkan sel dari Kolom A sampai D
                    $sheet->mergeCells("A{$totalRow}:D{$totalRow}");
                    
                    $styleArray = [
                        'font' => [
                            'color' => ['argb' => 'FFFFFFFF'],
                            'bold' => true,
                            'size' => 12
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FF173720']
                        ],
                        'alignment' => [ // Tambahkan perataan teks
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ];
                    
                    // Terapkan style dari Kolom A sampai E
                    $sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray($styleArray);
                    $sheet->getStyle("E{$totalRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                }
                // --- SELESAI PERUBAHAN ---
            },
        ];
    }
}