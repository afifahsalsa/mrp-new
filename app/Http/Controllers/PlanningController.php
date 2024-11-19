<?php

namespace App\Http\Controllers;

use App\Imports\OrderOriginalImport;
use App\Models\ProductionPlanning;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mrp.pp', [
            'title' => 'Production Planning'
        ]);
    }

    public function get_data(){
        $productionPlanningData = ProductionPlanning::select('*');
        return DataTables::of($productionPlanningData)
            ->editColumn('customer', function ($productionPlanningData) {
                return $productionPlanningData->customer;
            })->editColumn('model', function ($productionPlanningData) {
                return $productionPlanningData->model;
            })->editColumn('kodefgs', function ($productionPlanningData) {
                return $productionPlanningData->kodefgs;
            })->editColumn('partnumber', function ($productionPlanningData) {
                return $productionPlanningData->partnumber;
            })->editColumn('kategori', function ($productionPlanningData) {
                return $productionPlanningData->kategori;
            })->editColumn('bulan_1', function ($productionPlanningData) {
                return $productionPlanningData->bulan_1;
            })->editColumn('bulan_2', function ($productionPlanningData) {
                return $productionPlanningData->bulan_2;
            })->editColumn('bulan_3', function ($productionPlanningData) {
                return $productionPlanningData->bulan_3;
            })->editColumn('bulan_4', function ($productionPlanningData) {
                return $productionPlanningData->bulan_4;
            })->editColumn('bulan_5', function ($productionPlanningData) {
                return $productionPlanningData->bulan_5;
            })->editColumn('bulan_6', function ($productionPlanningData) {
                return $productionPlanningData->bulan_6;
            })->editColumn('bulan_7', function ($productionPlanningData) {
                return $productionPlanningData->bulan_7;
            })->editColumn('bulan_8', function ($productionPlanningData) {
                return $productionPlanningData->bulan_8;
            })->editColumn('bulan_9', function ($productionPlanningData) {
                return $productionPlanningData->bulan_9;
            })->editColumn('bulan_10', function ($productionPlanningData) {
                return $productionPlanningData->bulan_10;
            })->editColumn('bulan_11', function ($productionPlanningData) {
                return $productionPlanningData->bulan_11;
            })->editColumn('bulan_12', function ($productionPlanningData) {
                return $productionPlanningData->bulan_12;
            })->editColumn('bulan', function ($productionPlanningData) {
                return $productionPlanningData->bulan;
            })->editColumn('tahun', function ($productionPlanningData) {
                return $productionPlanningData->tahun;
            })->editColumn('total', function ($productionPlanningData) {
                return $productionPlanningData->total;
            })->editColumn('average', function ($productionPlanningData) {
                return $productionPlanningData->average;
            })->rawColumns(['customer', 'model', 'kodefgs', 'partnumber', 'kategori', 'bulan_1', 'bulan_2', 'bulan_3', 'bulan_4', 'bulan_5', 'bulan_6', 'bulan_7', 'bulan_8', 'bulan_9', 'bulan_10', 'bulan_11', 'bulan_12', 'bulan', 'tahun', 'total', 'average'])->make(true);
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:csv,xlx,xlsx',
            'month' => 'required'
        ]);
        $dataArray = [];
        $import = new OrderOriginalImport($dataArray, $request->month);
        $orderValue = Excel::toArray($import, $request->file('file'));
        $rowCount = 0;
        $month = $request->month;
        $convertMonth = DateTime::createFromFormat('Y-m', $month);

        foreach ($orderValue as $ov) {
            foreach ($ov as $idx => $i) {
                if ($idx > 0) {
                    $subset = array_slice($i, 5, 12, true);
                    $sum = array_sum($subset);
                    $avg = count($subset) > 0 ? array_sum($subset) / count($subset) : 0;

                    if(empty($i[2]) || $i[2] == '-' || $i[2] == null){
                        continue;
                    }

                    ProductionPlanning::create([
                        'customer' => $i[0] ?? '-',
                        'model' => $i[1] ?? '-',
                        'kodefgs' => $i[2] ?? '-',
                        'partnumber' => $i[3] ?? '-',
                        'kategori' => $i[4] ?? '-',
                        'bulan_1' => $i[5] ?? 0,
                        'bulan_2' => $i[6] ?? 0,
                        'bulan_3' => $i[7] ?? 0,
                        'bulan_4' => $i[8] ?? 0,
                        'bulan_5' => $i[9] ?? 0,
                        'bulan_6' => $i[10] ?? 0,
                        'bulan_7' => $i[11] ?? 0,
                        'bulan_8' => $i[12] ?? 0,
                        'bulan_9' => $i[13] ?? 0,
                        'bulan_10' => $i[14] ?? 0,
                        'bulan_11' => $i[15] ?? 0,
                        'bulan_12' => $i[16] ?? 0,
                        'bulan' => $convertMonth->format('F'),
                        'tahun' => $convertMonth->format('Y'),
                        'total' => $sum,
                        'average' => $avg
                    ]);
                    $rowCount++;
                }
            }
        }
        return back()->with([
            'status' => 'success',
            'message' => 'Import berhasil',
            'rowCount' => $rowCount
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
