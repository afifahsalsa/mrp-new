<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class PriceImport implements ToArray
{
    private $dataArray;
    private $date;

    public function __construct($dataArray, $date) // Hapus tanda &
    {
        $this->dataArray = $dataArray; // Tidak perlu referensi
        $this->date = $date;
    }

    public function array(array $rows)
    {
        $this->dataArray = $rows;
        foreach ($rows as $i => $row) {
            if ($i > 0) {
                // Lakukan proses pada baris jika diperlukan
            }
        }
    }
}
