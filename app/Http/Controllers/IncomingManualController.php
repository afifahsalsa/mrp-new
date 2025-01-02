<?php

namespace App\Http\Controllers;

use App\Imports\IncomingManualImport;
use App\Models\IncomingManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class IncomingManualController extends Controller
{
    public function index()
    {
        $monthIncoming = IncomingManual::selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('YEAR(date), MONTH(date) DESC')
            ->get();

        // $uniquePOs = IncomingManual::distinct('purchase_order')
        //     ->whereNotNull('purchase_order')
        //     ->pluck('purchase_order');

        return view('incoming-manual.choose', [
            'title' => 'Index Incoming Manual',
            'monthIncoming' => $monthIncoming
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/Format Impor Incoming Manual.xlsx');
        return response()->download($filePath);
    }

    public function get_data(Request $request, $year, $month){
        $imData = IncomingManual::whereYear('date', $year)
                ->whereMonth('date', $month);
        // if ($request->has(''))
        return DataTables::of($imData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx, csv',
            'date' => 'required'
        ]);
        $dataArray = [];
        $import = new IncomingManualImport($dataArray, $request->date);
        $incomingVal = Excel::toArray($import, $request->file('file'));
        $rowCountIM = 0;
        $tempData = [];
        $updateIM = 0;
        $cekDate = IncomingManual::where(DB::raw("FORMAT(date, 'yyyy MM')"), '=', date('Y m', strtotime($request->date)))->get();

        foreach ($incomingVal as $iv){
            foreach ($iv as $idx => $i){
                if ($idx > 0){
                    $convertDateArrived = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($i[1]);
                    if ($convertDateArrived->format('d') >= 20) {
                        return back()->with([
                            'swal' => [
                                'type' => 'error',
                                'title' => 'Import Gagal!',
                                'text' => "Tanggal kedatangan harus kurang dari 20."
                            ]
                        ]);
                    }

                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($i){
                        return $value['purchase_order'] == $i[4] && $value['item_number'] == $i[2];
                    });

                    if($duplicateInArray){
                        $duplicateItems[] = $i[2];
                        $duplicatePO[] = $i[4];
                    } else {
                        $tempData[] = [
                            'supplier' => $i[0],
                            'tgl_kedatangan' => $convertDateArrived,
                            'item_number' => $i[2],
                            'part_number' => $i[3],
                            'purchase_order' => $i[4],
                            'qty' => $i[5],
                            'date' => $request->date
                        ];
                    }
                }
            }
        }

        if(!empty($duplicateItems) && !empty($duplicatePO)){
            $uniqueDuplicateItems = array_unique($duplicateItems);
            $uniqueDuplicatePO = array_unique($duplicatePO);
            $tableHtml = '<div class="table-responsive">';
            $tableHtml .= '<p><strong>Terdapat duplikasi pada item number dan purchase order berikut:</strong></p>';
            $tableHtml .= '<table class="table table-bordered table-striped">';
            $tableHtml .= '<thead><tr><th>Item Number</th><th>Purchase Order</th></tr></thead>';
            $tableHtml .= '<tbody>';
            foreach ($uniqueDuplicateItems as $index => $item) {
                $im = $uniqueDuplicatePO[$index] ?? '';
                $tableHtml .= "<tr><td>{$item}</td><td>{$im}</td></tr>";
            }
            $tableHtml .= '</tbody></table></div>';

            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal!',
                    'html' => $tableHtml
                ]
            ]);
        }

        if(empty($i[5])){
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal!',
                    'text' => "Kolom quantity tidak boleh blank!"
                ]
            ]);
        }

        if(empty($i[2]) && empty($i[4])){
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal!',
                    'text' => "Kolom item number dan purchase order tidak boleh blank."
                ]
            ]);
        }

        if($cekDate){
            foreach ($tempData as $data){
                $itemInCekDate = collect($cekDate)->first(function ($value) use ($data) {
                    return $value['item_number'] === $data['item_number'] && $value['purchase_order'] === $data['purchase_order'];
                });
                if ($itemInCekDate) {
                    IncomingManual::where([
                        ['item_number', '=', $data['item_number']],
                        ['purchase_order', '=', $data['purchase_order']]
                    ])->update($data);
                    $updateIM++;
                } else {
                    IncomingManual::create($data);
                    $rowCountIM++;
                }
            }
        }

        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'Import Berhasil!',
                'text' => "Berhasil mengimpor {$rowCountIM} baris data."
            ]
        ]);
    }

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
