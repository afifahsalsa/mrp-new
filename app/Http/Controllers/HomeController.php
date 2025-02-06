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
        // $poData = $this->getPoData($startDate, $endDate);
        $planProdData = $this->getPlanProdData();

        return view('dashboard', array_merge([
            'title' => 'Dashboard',
            'startDate' => $startDate,
            'endDate' => $endDate,
            // 'poData' => $poData
        ], $stockData, $planProdData));
    }

    public function getChartData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $stockData = $this->getStockData($startDate, $endDate);
        // $poData = $this->getPoData($startDate, $endDate);
        $planProdData = $this->getPlanProdData();

        return response()->json(array_merge($stockData, $planProdData));
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
                DB::raw('COUNT(id) AS quantity_percentage')
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

    // private function getPoData($startDate = null, $endDate = null)
    // {
    //     $filterDate = $startDate ? Carbon::parse($startDate) : Carbon::now();
    //     $filterMonth = $filterDate->format('m');
    //     $filterYear = $filterDate->format('Y');
    //     $prevMonth = $filterDate->copy()->subMonth();

    //     $query = DB::table('open_po')
    //         ->select(
    //             DB::raw("
    //             SUM(CASE WHEN MONTH(created_at) = {$filterMonth}
    //                      AND YEAR(created_at) = {$filterYear}
    //                      AND ket_lt = 'LEAD TIME' THEN delivery_reminder ELSE 0 END) AS qty_lt_now,
    //             SUM(CASE WHEN MONTH(created_at) = {$filterMonth}
    //                      AND YEAR(created_at) = {$filterYear}
    //                      AND ket_lt = 'LEAD TIME' THEN amount ELSE 0 END) AS amount_lt_now,
    //             SUM(CASE WHEN MONTH(created_at) = {$prevMonth->format('m')}
    //                      AND YEAR(created_at) = {$prevMonth->format('Y')}
    //                      AND ket_lt = 'LEAD TIME' THEN delivery_reminder ELSE 0 END) AS qty_lt_prev,
    //             SUM(CASE WHEN MONTH(created_at) = {$prevMonth->format('m')}
    //                      AND YEAR(created_at) = {$prevMonth->format('Y')}
    //                      AND ket_lt = 'LEAD TIME' THEN amount ELSE 0 END) AS amount_lt_prev,
    //             SUM(CASE WHEN MONTH(created_at) = {$filterMonth}
    //                      AND YEAR(created_at) = {$filterYear}
    //                      AND ket_lt = 'NON LEAD TIME' THEN delivery_reminder ELSE 0 END) AS qty_nlt_now,
    //             SUM(CASE WHEN MONTH(created_at) = {$filterMonth}
    //                      AND YEAR(created_at) = {$filterYear}
    //                      AND ket_lt = 'NON LEAD TIME' THEN amount ELSE 0 END) AS amount_nlt_now,
    //             SUM(CASE WHEN MONTH(created_at) = {$prevMonth->format('m')}
    //                      AND YEAR(created_at) = {$prevMonth->format('Y')}
    //                      AND ket_lt = 'NON LEAD TIME' THEN delivery_reminder ELSE 0 END) AS qty_nlt_prev,
    //             SUM(CASE WHEN MONTH(created_at) = {$prevMonth->format('m')}
    //                      AND YEAR(created_at) = {$prevMonth->format('Y')}
    //                      AND ket_lt = 'NON LEAD TIME' THEN amount ELSE 0 END) AS amount_nlt_prev
    //         ")
    //         )->first();

    //     return [
    //         'qty_lt_now' => $query->qty_lt_now ?? 0,
    //         'amount_lt_now' => $query->amount_lt_now ?? 0,
    //         'qty_lt_prev' => $query->qty_lt_prev ?? 0,
    //         'amount_lt_prev' => $query->amount_lt_prev ?? 0,
    //         'qty_nlt_now' => $query->qty_nlt_now ?? 0,
    //         'amount_nlt_now' => $query->amount_nlt_now ?? 0,
    //         'qty_nlt_prev' => $query->qty_nlt_prev ?? 0,
    //         'amount_nlt_prev' => $query->amount_nlt_prev ?? 0,
    //         'filter_month' => $filterDate->format('M Y'),
    //         'prev_month' => $prevMonth->format('M Y')
    //     ];
    // }

    private function getPlanProdData()
    {
        $query = DB::table('mpp')
            ->select(
                DB::raw("
                SUM(ori_cust_bulan_1) AS ori_cust_bulan_1,
                SUM(ori_cust_bulan_2) AS ori_cust_bulan_2,
                SUM(ori_cust_bulan_3) AS ori_cust_bulan_3,
                SUM(ori_cust_bulan_4) AS ori_cust_bulan_4,
                SUM(ori_cust_bulan_5) AS ori_cust_bulan_5,
                SUM(ori_cust_bulan_6) AS ori_cust_bulan_6,
                SUM(ori_cust_bulan_7) AS ori_cust_bulan_7,
                SUM(ori_cust_bulan_8) AS ori_cust_bulan_8,
                SUM(ori_cust_bulan_9) AS ori_cust_bulan_9,
                SUM(ori_cust_bulan_10) AS ori_cust_bulan_10,
                SUM(ori_cust_bulan_11) AS ori_cust_bulan_11,
                SUM(ori_cust_bulan_12) AS ori_cust_bulan_12
            ")
            )
            ->first();

        $chartData = [
            'bulan_1' => $query->bulan_1 ?? 0,
            'bulan_2' => $query->bulan_2 ?? 0,
            'bulan_3' => $query->bulan_3 ?? 0,
            'bulan_4' => $query->bulan_4 ?? 0,
            'bulan_5' => $query->bulan_5 ?? 0,
            'bulan_6' => $query->bulan_6 ?? 0,
            'bulan_7' => $query->bulan_7 ?? 0,
            'bulan_8' => $query->bulan_8 ?? 0,
            'bulan_9' => $query->bulan_9 ?? 0,
            'bulan_10' => $query->bulan_10 ?? 0,
            'bulan_11' => $query->bulan_11 ?? 0,
            'bulan_12' => $query->bulan_12 ?? 0,
        ];

        return $chartData;
    }
}
