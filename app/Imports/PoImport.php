<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class PoImport implements ToArray
{
    private $dataArray;

    public function __construct(&$dataArray)
    {
        $this->dataArray = &$dataArray;
    }

    public function array(array $rows)
    {
        $this->dataArray = $rows;
        foreach ($rows as $i => $row) {
            if ($i > 0) {

            }
        }
    }
}
