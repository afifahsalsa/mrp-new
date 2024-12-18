<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class VisualizationController extends Controller
{
    public function bufferStokChart()
    {
        $bufferData = DB::table('buffer')
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereRaw('date >= DATEADD(month, -6, GETDATE())')
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get();

        $months = collect(range(1, 6))->map(function($offset) {
            $monthDate = now()->subMonths(6 - $offset);
            return [
                'month' => $monthDate->month,
                'month_name' => $monthDate->format('M Y'),
                'count' => 0
            ];
        });

        $processedBufferData = $months->map(function($month) use ($bufferData) {
            $matchingData = $bufferData->firstWhere('month', $month['month']);
            return [
                'month' => $month['month_name'],
                'count' => $matchingData ? $matchingData->count : 0
            ];
        });

        $percentageData = DB::table('stok')
            ->select(
                DB::raw("
                    CASE
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) > 100 THEN '>100%'
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) = 100 THEN '100%'
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) < 100 AND
                             CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) > 75 THEN '100-75%'
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) <= 75 AND
                             CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) > 50 THEN '75-50%'
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) <= 50 AND
                             CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) > 25 THEN '50-25%'
                        ELSE '<25%'
                    END AS percentage_range
                "),
                DB::raw('COUNT(*) AS quantity_percentage')
            )
            ->groupBy(
                DB::raw("
                    CASE
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) > 100 THEN '>100%'
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) = 100 THEN '100%'
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) < 100 AND
                             CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) > 75 THEN '100-75%'
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) <= 75 AND
                             CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) > 50 THEN '75-50%'
                        WHEN CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) <= 50 AND
                             CAST(REPLACE(ISNULL(percentage, '0%'), '%', '') AS FLOAT) > 25 THEN '50-25%'
                        ELSE '<25%'
                    END
                ")
            )
            ->get();

        return view('chart_buff_stok', [
            'title' => 'Visualization Buffer & Stock',
            'bufferData' => $processedBufferData,
            'percentageData' => $percentageData
        ]);
    }
}
