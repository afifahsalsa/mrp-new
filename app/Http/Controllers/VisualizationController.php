<?php

namespace App\Http\Controllers;

use App\Models\Buffer;
use Illuminate\Http\Request;

class VisualizationController extends Controller
{
    public function bufferStokChart()
    {
        $bufferData = Buffer::selectRaw('MONTH([date]) as month, count(*) as count')
            ->groupByRaw('MONTH([date])')
            ->get();
        return view('chart_buff_stok', [
            'title' => 'Visualization Buffer & Stock',
            'bufferData' => $bufferData
        ]);
    }
}
