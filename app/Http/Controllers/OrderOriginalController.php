<?php

namespace App\Http\Controllers;

use App\Imports\OrderOriginalImport;
use App\Models\OrderOriginal;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use Yajra\DataTables\Facades\DataTables;

class OrderOriginalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mrp.order_original', [
            'title' => 'Order Original'
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/format order original & production planning.xlsx');
        return response()->download($filePath);
    }

    public function get_data()
    {
        $orderOriginalData = OrderOriginal::select('*');
        return DataTables::of($orderOriginalData)
            ->editColumn('customer', function ($orderOriginalData) {
                return $orderOriginalData->customer;
            })->editColumn('model', function ($orderOriginalData) {
                return $orderOriginalData->model;
            })->editColumn('kodefgs', function ($orderOriginalData) {
                return $orderOriginalData->kodefgs;
            })->editColumn('partnumber', function ($orderOriginalData) {
                return $orderOriginalData->partnumber;
            })->editColumn('kategori', function ($orderOriginalData) {
                return $orderOriginalData->kategori;
            })->editColumn('bulan_1', function ($orderOriginalData) {
                return $orderOriginalData->bulan_1;
            })->editColumn('bulan_2', function ($orderOriginalData) {
                return $orderOriginalData->bulan_2;
            })->editColumn('bulan_3', function ($orderOriginalData) {
                return $orderOriginalData->bulan_3;
            })->editColumn('bulan_4', function ($orderOriginalData) {
                return $orderOriginalData->bulan_4;
            })->editColumn('bulan_5', function ($orderOriginalData) {
                return $orderOriginalData->bulan_5;
            })->editColumn('bulan_6', function ($orderOriginalData) {
                return $orderOriginalData->bulan_6;
            })->editColumn('bulan_7', function ($orderOriginalData) {
                return $orderOriginalData->bulan_7;
            })->editColumn('bulan_8', function ($orderOriginalData) {
                return $orderOriginalData->bulan_8;
            })->editColumn('bulan_9', function ($orderOriginalData) {
                return $orderOriginalData->bulan_9;
            })->editColumn('bulan_10', function ($orderOriginalData) {
                return $orderOriginalData->bulan_10;
            })->editColumn('bulan_11', function ($orderOriginalData) {
                return $orderOriginalData->bulan_11;
            })->editColumn('bulan_12', function ($orderOriginalData) {
                return $orderOriginalData->bulan_12;
            })->editColumn('bulan', function ($orderOriginalData) {
                return $orderOriginalData->bulan;
            })->editColumn('tahun', function ($orderOriginalData) {
                return $orderOriginalData->tahun;
            })->editColumn('total', function ($orderOriginalData) {
                return $orderOriginalData->total;
            })->editColumn('average', function ($orderOriginalData) {
                return $orderOriginalData->average;
            })->rawColumns(['customer', 'model', 'kodefgs', 'partnumber', 'kategori', 'bulan_1', 'bulan_2', 'bulan_3', 'bulan_4', 'bulan_5', 'bulan_6', 'bulan_7', 'bulan_8', 'bulan_9', 'bulan_10', 'bulan_11', 'bulan_12', 'bulan', 'tahun', 'total', 'average'])->make(true);
    }

    public function import(Request $request)
    {
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

                    OrderOriginal::create([
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
    public function destroy(Request $request)
    {
        dd($request);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|string',
        ]);
        $ids = $request->ids;
        DB::table('open_po')
            ->whereIn('id', $ids)
            ->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
