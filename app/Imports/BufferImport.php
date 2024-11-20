<?php

namespace App\Imports;

use App\Models\Buffer;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class BufferImport implements ToArray, WithCalculatedFormulas
{
    private $rowCount = 0;
    private $date;

    public function __construct( $date)
    {
        $this->date = $date;
    }

    public function array(array $rows)
    {
        $this->date;
        foreach ($rows as $i => $row){
            if ($i > 0){

            }
        }
        // foreach ($rows as $i => $row) {
        //     if ($i > 0) {
        //         Buffer::create([
        //             'item_number' => $row[0],
        //             'part_number' => $row[1],
        //             'product_name' => $row[2],
        //             'usage' => $row[3],
        //             'lt' => $row[4],
        //             'supplier' => $row[5],
        //             'qty' => intval($row[6]),
        //             'date' => $this->date
        //         ]);
        //         $this->rowCount++;
        //     }
        // }
    }
}
