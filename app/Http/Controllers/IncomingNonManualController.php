<?php

namespace App\Http\Controllers;

use App\Imports\IncomingNonManualImport;
use App\Models\IncomingNonManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class IncomingNonManualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthIncomingNon = IncomingNonManual::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at) DESC')
            ->get();
        $uniquePOs = IncomingNonManual::distinct('item_number')
            ->whereNotNull('item_number')
            ->pluck('item_number');
        return view('incoming-non-manual.choose', [
            'title' => 'Index Incoming Non Manual',
            'monthIncomingNon' => $monthIncomingNon,
            'uniquePOs' => $uniquePOs
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/Incoming Non Manual.xlsx');
        return response()->download($filePath);
    }

    public function get_data(Request $request, $year, $month)
    {
        $inmData = IncomingNonManual::whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
        return DataTables::of($inmData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx, csv'
        ]);
        $dataArray = [];
        $incomingNonVal = Excel::toArray(new IncomingNonManualImport($dataArray), $request->file('file'));
        $rowCountINM = 0;
        $tempData = [];
        $updateINM = 0;

        foreach ($incomingNonVal as $inv) {
            foreach ($inv as $idx => $i) {
                if ($idx > 0) {
                    $convertDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($i[3]);
                    $convertCreatedDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($i[4]);
                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($i) {
                        return $value['purchase_order'] == $i[1] && $value['item_number'] == $i[0] && $value['internal_product_receipt'] == $i[6];
                    });

                    if ($duplicateInArray) {
                        $duplicateItems[] = $i[0];
                        $duplicatePO[] = $i[1];
                    } else {
                        $tempData[] = [
                            'item_number' => $i[0],
                            'purchase_order' => $i[1],
                            'name' => $i[2],
                            'date' => $convertDate,
                            'created_date_and_time' => $convertCreatedDate,
                            'product_receipt' => $i[5],
                            'internal_product_receipt' => $i[6],
                            'product_reference' => $i[7],
                            'ordered' => $i[8],
                            'received' => $i[9],
                            'created_at' => date(now())
                        ];
                    }
                }
            }
        }

        if (!empty($duplicateItems) && !empty($duplicatePO)) {
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

        foreach ($tempData as $data) {
            $cekDate = IncomingNonManual::where(DB::raw("FORMAT(created_at, 'yyyy MM')"), '=', date('Y m', strtotime($data['created_at'])))->get();
            if ($cekDate) {
                $itemInCekDate = collect($cekDate)->first(function ($value) use ($data){
                    return $value['item_number'] === $data['item_number'] && $value['purchase_order'] === $data['purchase_order'];
                });
                if($itemInCekDate){
                    IncomingNonManual::where([
                        ['item_number', '=', $data['item_number']],
                        ['purchase_order', '=', $data['purchase_order']]
                    ])->update($data);
                    $updateINM++;
                } else {
                    IncomingNonManual::create($data);
                    $rowCountINM++;
                }
            }
        }

        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'Import Berhasil!',
                'text' => "Berhasil mengimpor {$rowCountINM} baris data."
            ]
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
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|string',
        ]);
        $ids = $request->ids;
        DB::table('incoming_non_manual')
            ->whereIn('id', $ids)
            ->delete();
        return response()->json([
            'success' => true,
            'title' => 'Data berhasil dihapus'
        ]);
    }
}
