<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class PoImport implements ToArray
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
