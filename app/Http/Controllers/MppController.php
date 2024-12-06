<?php

namespace App\Http\Controllers;

use App\Imports\MppImport;
use App\Models\Mpp;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MppController extends Controller
{
    public function index_choose_order_customer()
    {
        $monthCust = Mpp::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();
        return view('mpp.choose_order_customer', [
            'title' => 'Index Order Customer',
            'monthCust' => $monthCust
        ]);
    }

    public function index_choose_prod_plan()
    {
        $monthProdPlan = Mpp::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();
        return view('mpp.choose_prod_plan', [
            'title' => 'Index Production Planning',
            'monthProdPlan' => $monthProdPlan
        ]);
    }

    public function index_choose_max_unit()
    {
        $monthMaxUnit = Mpp::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();
        return view('mpp.choose_max_unit', [
            'title' => 'Index Production Planning',
            'monthMaxUnit' => $monthMaxUnit
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/Format Impor Mpp.xlsx');
        return response()->download($filePath);
    }

    public function get_data($year, $month){
        $mppData = Mpp::where('tahun', $year)
            ->where('bulan', $month);
        return DataTables::of($mppData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
            'month' => 'required'
        ]);

        $dataArray = [];
        $import = new MppImport($dataArray, $request->month);
        $mppValue = Excel::toArray($import, $request->file('file'));
        $tempData = [];
        $rowCountMpp = 0;
        $rowCountUpdateMpp = 0;
        $cekDate = Mpp::where('tahun', substr($request->month, 0, 4))
                    ->where('bulan', substr($request->month, 5, 2))
                    ->get();

        foreach ($mppValue as $mv){
            foreach($mv as $idx => $i){
                if($idx > 1){
                    if(empty($i[2]) || $i[2] == '-' || $i[2] == null){
                        continue;
                    }
                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($i, $request){
                        return $value['kodefgs'] == $i[2] && $value['partnumber'] == $i[3] && $value['tahun'] == substr($request->month, 0, 4) && $value['bulan'] == substr($request->month, 5, 2);
                    });

                    if($duplicateInArray){
                        $duplicateFgs[] = $i[2];
                        $duplicatePart[] = $i[3];
                    } else {
                        $tempData[] = [
                            'customer' => $i[0] ?? 'NULL',
                            'model' => $i[1] ?? 'NULL',
                            'kodefgs' => $i[2],
                            'partnumber' => $i[3],
                            'kategori' => $i[4] ?? 'NULL',
                            'ori_cust_bulan_1' => $i[5] ?? 0,
                            'ori_cust_bulan_2' => $i[6] ?? 0,
                            'ori_cust_bulan_3' => $i[7] ?? 0,
                            'ori_cust_bulan_4' => $i[8] ?? 0,
                            'ori_cust_bulan_5' => $i[9] ?? 0,
                            'ori_cust_bulan_6' => $i[10] ?? 0,
                            'ori_cust_bulan_7' => $i[11] ?? 0,
                            'ori_cust_bulan_8' => $i[12] ?? 0,
                            'ori_cust_bulan_9' => $i[13] ?? 0,
                            'ori_cust_bulan_10' => $i[14] ?? 0,
                            'ori_cust_bulan_11' => $i[15] ?? 0,
                            'ori_cust_bulan_12' => $i[16] ?? 0,
                            'prod_plan_bulan_1' => $i[17] ?? 0,
                            'prod_plan_bulan_2' => $i[18] ?? 0,
                            'prod_plan_bulan_3' => $i[19] ?? 0,
                            'prod_plan_bulan_4' => $i[20] ?? 0,
                            'prod_plan_bulan_5' => $i[21] ?? 0,
                            'prod_plan_bulan_6' => $i[22] ?? 0,
                            'prod_plan_bulan_7' => $i[23] ?? 0,
                            'prod_plan_bulan_8' => $i[24] ?? 0,
                            'prod_plan_bulan_9' => $i[25] ?? 0,
                            'prod_plan_bulan_10' => $i[26] ?? 0,
                            'prod_plan_bulan_11' => $i[27] ?? 0,
                            'prod_plan_bulan_12' => $i[28] ?? 0,
                            'max_bulan_1' => max($i[5], $i[17]),
                            'max_bulan_2' => max($i[6], $i[18]),
                            'max_bulan_3' => max($i[7], $i[19]),
                            'max_bulan_4' => max($i[8], $i[20]),
                            'max_bulan_5' => max($i[9], $i[21]),
                            'max_bulan_6' => max($i[10], $i[22]),
                            'max_bulan_7' => max($i[11], $i[23]),
                            'max_bulan_8' => max($i[12], $i[24]),
                            'max_bulan_9' => max($i[13], $i[25]),
                            'max_bulan_10' => max($i[14], $i[26]),
                            'max_bulan_11' => max($i[15], $i[27]),
                            'max_bulan_12' => max($i[16], $i[28]),
                            'bulan' => substr($request->month, 5, 2),
                            'tahun' => substr($request->month, 0, 4)
                        ];
                    }
                }
            }
        }

        if (!empty($duplicateFgs) && !empty($duplicatePart)){
            $uniqueDuplicateFgs = array_unique($duplicateFgs);
            $uniqueDuplicatePart = array_unique($duplicatePart);
            $duplicateFgsList = implode(', ', $uniqueDuplicateFgs);
            $duplicatePartList = implode(', ', $uniqueDuplicatePart);
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal!',
                    'text' => "Terdapat duplikasi pada Kode FGS: {$duplicateFgsList} dengan Part Number: {$duplicatePartList}"
                ]
            ]);
        }

        if($cekDate){
            foreach ($tempData as $data){
                $kodefgsInCekDate = collect($cekDate)->firstWhere('kodefgs', $data['kodefgs']);
                if($kodefgsInCekDate){
                    Mpp::where('kodefgs', $data['kodefgs'])->update($data);
                    $rowCountUpdateMpp++;
                } else {
                    Mpp::create($data);
                    $rowCountMpp++;
                }
            }
        } else {
            foreach ($tempData as $data){
                Mpp::create($data);
                $rowCountMpp++;
            }
        }

        if ($rowCountUpdateMpp > 0){
            return back()->with([
                'swal' => [
                    'type' => 'success',
                    'title' => 'Update data MPP!',
                    'text' => "Berhasil update {$rowCountUpdateMpp} baris dan impor {$rowCountMpp} baris data baru."
                ]
            ]);
        } else {
            return back()->with([
                'swal' => [
                    'type' => 'success',
                    'title' => 'Import Berhasil',
                    'text' => "Berhasil mengimpor {$rowCountMpp} baris data."
                ]
            ]);
        }

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
