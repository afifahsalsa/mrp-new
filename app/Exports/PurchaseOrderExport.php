<?php

namespace App\Exports;

use App\Models\OpenPo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseOrderExport implements FromQuery, WithEvents,WithCustomStartCell
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
        return OpenPo::query()
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->select(['purchase_order', 'item_number', 'product_name', 'purchase_requisition', 'tpqty', 'tpunit', 'tpsite', 'tpvendor', 'delivery_date', 'delivery_reminder', 'old_number_format', 'created_date_and_time', 'tpstatus', 'line_status', 'supplier_name', 'standar_datang', 'bulan_datang', 'lt', 'ket_late', 'ket_lt']);
    }

    public function startCell(): string{
        return 'A2';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->setCellValue('A1', 'Purchase Order');
                $event->sheet->setCellValue('B1', 'Item Number');
                $event->sheet->setCellValue('C1', 'Product Name');
                $event->sheet->setCellValue('D1', 'Purchase Requisition');
                $event->sheet->setCellValue('E1', 'tpqty');
                $event->sheet->setCellValue('F1', 'tpunit');
                $event->sheet->setCellValue('G1', 'tpsite');
                $event->sheet->setCellValue('H1', 'tpvendor');
                $event->sheet->setCellValue('I1', 'Delivery Date');
                $event->sheet->setCellValue('J1', 'Delivery Reminder');
                $event->sheet->setCellValue('K1', 'Old Number Format');
                $event->sheet->setCellValue('L1', 'Created Date and Time');
                $event->sheet->setCellValue('M1', 'tpstatus');
                $event->sheet->setCellValue('N1', 'Line Status');
                $event->sheet->setCellValue('O1', 'Supplier Name');
                $event->sheet->setCellValue('P1', 'Standar Datang');
                $event->sheet->setCellValue('Q1', 'Bulan Datang');
                $event->sheet->setCellValue('R1', 'LT');
                $event->sheet->setCellValue('S1', 'Keterangan Late');
                $event->sheet->setCellValue('T1', 'Keterangan LT');

                $style = $event->sheet->getStyle('A1:T1');
                $font = $style->getFont();
                $font->setBold(true);

                $event->sheet->setAutoFilter('A1:T1');
            }
        ];
    }
}
