<?php

namespace App\Imports;

use App\Models\Stok;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;

class StokImport implements ToArray
{
    private $dataArray;
    private $date;

    public function __construct(&$dataArray, $date)
    {
        $this->dataArray = &$dataArray;
        $this->date = $date;
    }


    public function array(array $rows)
    {
        $this->dataArray = $rows;
        $this->date;
        foreach ($rows as $i => $row) {
            if ($i > 0) {
            }
        }
    }
}
