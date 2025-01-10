<?php

namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToArray;

class IncomingNonManualImport implements ToArray
{
    private $dataArray;

    public function __construct(&$dataArray)
    {
        $this->dataArray = &$dataArray;
    }

    public function array(array $array)
    {
        $this->dataArray = $array;
        foreach ($array as $i => $row){
            if($i > 0){

            }
        }
    }
}
