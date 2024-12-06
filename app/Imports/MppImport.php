<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class MppImport implements ToArray
{
    private $dataArray;
    private $month;

    public function __construct(&$dataArray, $month)
    {
        $this->dataArray = &$dataArray;
        $this->month = $month;
    }

    public function array(array $rows)
    {
        $this->dataArray = $rows;
        $this->month;
        foreach ($rows as $i => $row) {
            if ($i > 0) {

            }
        }
    }
}
