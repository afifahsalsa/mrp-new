<?php

namespace App\Exports;

use App\Models\Stok;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StokExport implements FromQuery, WithEvents, WithCustomStartCell
{
    protected $year;
    protected $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function query()
    {
        return Stok::query()
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->select(['item_number', 'part_number', 'product_name', 'lt', 'stok', 'li', 'qty_buffer', 'percentage', 'date']);
    }

    public function startCell(): string{
        return 'A2';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->setCellValue('A1', 'Item Number');
                $event->sheet->setCellValue('B1', 'Part Number');
                $event->sheet->setCellValue('C1', 'Product Name');
                $event->sheet->setCellValue('D1', 'LT');
                $event->sheet->setCellValue('E1', 'Stok');
                $event->sheet->setCellValue('F1', 'L/I');
                $event->sheet->setCellValue('G1', 'Quantity Buffer');
                $event->sheet->setCellValue('H1', 'Percentage');
                $event->sheet->setCellValue('I1', 'Date');

                $style = $event->sheet->getStyle('A1:I1');
                $font = $style->getFont();
                $font->setBold(true);

                $event->sheet->setAutoFilter('A1:I1');
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                // $event->sheet->getColumnDimension('B')->setAutoSize(true);
                // $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                // $event->sheet->getColumnDimension('G')->setAutoSize(true);
                // $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
            }
        ];
    }
}
