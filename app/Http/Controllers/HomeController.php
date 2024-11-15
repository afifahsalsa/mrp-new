<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $stockData = $this->getStockData($startDate, $endDate);
        $poData = $this->getPoData($startDate, $endDate);

        return view('dashboard', array_merge([
            'title' => 'Dashboard',
            'startDate' => $startDate,
            'endDate' => $endDate
        ], $stockData, $poData));
    }

    public function getChartData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $stockData = $this->getStockData($startDate, $endDate);
        $poData = $this->getPoData($startDate, $endDate);

        return response()->json(array_merge($stockData, $poData));
    }

    private function getStockData($startDate = null, $endDate = null)
    {
        // Base query
        $query = DB::table('stok')
            ->select(
                DB::raw("
                    CASE
                        WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) > 100 THEN '>100%'
                        WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) = 100 THEN '100%'
                        WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) < 100 AND CAST(REPLACE(percentage, '%', '') AS FLOAT) > 75 THEN '100-75%'
                        WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) <= 75 AND CAST(REPLACE(percentage, '%', '') AS FLOAT) > 50 THEN '75-50%'
                        WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) <= 50 AND CAST(REPLACE(percentage, '%', '') AS FLOAT) > 25 THEN '50-25%'
                        ELSE '<25%'
                    END AS percentage_range
                "),
                DB::raw('COUNT(buffer_id) AS quantity_percentage')
            );

        // Apply date filters if provided
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $dataStock = $query->groupBy(DB::raw("
                CASE
                    WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) > 100 THEN '>100%'
                    WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) = 100 THEN '100%'
                    WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) < 100 AND CAST(REPLACE(percentage, '%', '') AS FLOAT) > 75 THEN '100-75%'
                    WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) <= 75 AND CAST(REPLACE(percentage, '%', '') AS FLOAT) > 50 THEN '75-50%'
                    WHEN CAST(REPLACE(percentage, '%', '') AS FLOAT) <= 50 AND CAST(REPLACE(percentage, '%', '') AS FLOAT) > 25 THEN '50-25%'
                    ELSE '<25%'
                END
            "))
            ->orderBy('quantity_percentage', 'DESC')
            ->get();

        return [
            'upSeratus' => $dataStock->where('percentage_range', '>100%')->pluck('quantity_percentage')->first() ?? 0,
            'seratus' => $dataStock->where('percentage_range', '100%')->pluck('quantity_percentage')->first() ?? 0,
            'tujulima' => $dataStock->where('percentage_range', '100-75%')->pluck('quantity_percentage')->first() ?? 0,
            'limapuluh' => $dataStock->where('percentage_range', '75-50%')->pluck('quantity_percentage')->first() ?? 0,
            'dualima' => $dataStock->where('percentage_range', '50-25%')->pluck('quantity_percentage')->first() ?? 0,
            'nol' => $dataStock->where('percentage_range', '<25%')->pluck('quantity_percentage')->first() ?? 0
        ];
    }

    private function getPoData($startDate = null, $endDate = null)
    {
        $query = DB::table('open_po')
            ->select(
                DB::raw("
                SUM(CASE WHEN bulan_datang = MONTH(GETDATE())
                            AND ket_lt = 'LEAD TIME' THEN delivery_reminder ELSE 0 END) AS qty_lt_now,
                SUM(CASE WHEN bulan_datang = MONTH(GETDATE())
                            AND ket_lt = 'LEAD TIME' THEN amount ELSE 0 END) AS amount_lt_now,
                SUM(CASE WHEN bulan_datang = MONTH(GETDATE()) - 1
                            AND ket_lt = 'LEAD TIME' THEN delivery_reminder ELSE 0 END) AS qty_lt_prev,
                SUM(CASE WHEN bulan_datang = MONTH(GETDATE()) - 1
                            AND ket_lt = 'LEAD TIME' THEN amount ELSE 0 END) AS amount_lt_prev,
                SUM(CASE WHEN bulan_datang = MONTH(GETDATE())
                            AND ket_lt = 'NON LEAD TIME' THEN delivery_reminder ELSE 0 END) AS qty_nlt_now,
                SUM(CASE WHEN bulan_datang = MONTH(GETDATE())
                            AND ket_lt = 'NON LEAD TIME' THEN amount ELSE 0 END) AS amount_nlt_now,
                SUM(CASE WHEN bulan_datang = MONTH(GETDATE()) - 1
                            AND ket_lt = 'NON LEAD TIME' THEN delivery_reminder ELSE 0 END) AS qty_nlt_prev,
                SUM(CASE WHEN bulan_datang = MONTH(GETDATE()) - 1
                            AND ket_lt = 'NON LEAD TIME' THEN amount ELSE 0 END) AS amount_nlt_prev
            ")
            )->first();

        return [
            'qty_lt_now' => $query->qty_lt_now ?? 0,
            'amount_lt_now' => $query->amount_lt_now ?? 0,
            'qty_lt_prev' => $query->qty_lt_prev ?? 0,
            'amount_lt_prev' => $query->amount_lt_prev ?? 0,
            'qty_nlt_now' => $query->qty_nlt_now ?? 0,
            'amount_nlt_now' => $query->amount_nlt_now ?? 0,
            'qty_nlt_prev' => $query->qty_nlt_prev ?? 0,
            'amount_nlt_prev' => $query->amount_nlt_prev ?? 0,
        ];
    }
}
