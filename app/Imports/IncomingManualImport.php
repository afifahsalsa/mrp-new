<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class IncomingManualImport implements ToArray
{
    private $dataArray;
    private $date;

    public function __construct(&$dataArray, $date)
    {
        $this->dataArray = &$dataArray;
        $this->date = $date;
    }

    public function array(array $array)
    {
        $this->dataArray = $array;
        $this->date;
        foreach ($array as $i => $row){
            if($i > 0){

            }
        }
    }
}
