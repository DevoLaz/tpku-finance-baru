<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class BebansExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    protected $bebans;
    protected $totalBeban;

    public function __construct($bebans, $totalBeban)
    {
        $this->bebans = $bebans;
        $this->totalBeban = $totalBeban;
    }

    public function array(): array
    {
        $exportData = [];

        // Menambahkan setiap baris data beban
        foreach ($this->bebans as $beban) {
            $exportData[] = [
                'tanggal'    => Carbon::parse($beban->tanggal)->format('d-m-Y'),
                'nama_beban' => $beban->nama,
                'kategori'   => $beban->kategori->nama_kategori ?? 'N/A',
                'keterangan' => $beban->keterangan,
                'jumlah'     => $beban->jumlah,
            ];
        }

        // Menambahkan baris total di akhir
        if ($this->bebans->isNotEmpty()) {
            $exportData[] = ['', '', '', '', '']; // Baris kosong
            $exportData[] = [
                'TOTAL BEBAN OPERASIONAL',
                '', '', '',
                $this->totalBeban,
            ];
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Beban',
            'Kategori',
            'Keterangan',
            'Jumlah',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF173720');
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');
        
        // Atur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(20);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Format Rupiah untuk kolom Jumlah
                $sheet->getStyle('E2:E' . $lastRow)->getNumberFormat()
                      ->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');

                // Jika ada data, format baris total
                if ($this->bebans->isNotEmpty()) {
                    $totalRow = $lastRow;
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
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ];
                    
                    $sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray($styleArray);
                }
            },
        ];
    }
}