<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseOrderExport;
use App\Imports\PoImport;
use App\Models\OpenPo;
use App\Models\Stok;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OpenPoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $monthPO = OpenPo::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at) DESC')
            ->get();
        return view('open-po.choose', [
            'title' => 'Index Open PO',
            'monthPO' => $monthPO
        ]);
    }

    public function index_edit($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        return view('open-po.index', [
            'title' => 'Edit Open PO',
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName
        ]);
    }

    public function get_format(){
        $filePath = public_path('doc/Format Purchase Order.xlsx');
        return response()->download($filePath);
    }

    public function get_unique_po($year, $month){
        $uniquePO = OpenPo::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->distinct('purchase_order')
            ->pluck('purchase_order')
            ->filter(function ($value){
                return $value !== null;
            })
            ->values()
            ->toArray();
        return response()->json($uniquePO);
    }

    public function get_data(Request $request, $year, $month)
    {
        $poData = OpenPo::whereYear('date', $year)
            ->whereMonth('date', $month);
        if ($request->has('purchase_order') && $request->purchase_order !== ''){
            $poData = $poData->where('purchase_order', $request->purchase_order);
        }
        return DataTables::of($poData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx',
            'date' => 'required'
        ]);
        $dataArray = [];
        $import = new PoImport($dataArray, $request->date);
        $poVal = Excel::toArray($import, $request->file('file'));
        $emptyPRItems = [];
        $rowCountPo = 0;
        $updatePO = 0;
        $duplicateItems = [];
        $duplicatePO = [];
        $tempData = [];
        $cekDate = OpenPo::where(DB::raw("FORMAT(date, 'yyyy MM')"), '=', date('Y m', strtotime($request->date)))->get();

        foreach ($poVal as $pv) {
            foreach ($pv as $idx => $i) {
                if ($idx > 0) {
                    $dataStok = Stok::where('item_number', $i[1])
                        ->orderBy('date', 'desc')
                        ->first();
                    $convertDelivery = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($i[8]);
                    $convertCreated = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($i[11]);

                    if ($dataStok && $dataStok->lt == '0') {
                        $lt = '0';
                    } elseif ($dataStok && !is_numeric($dataStok->lt)) {
                        $lt = substr($dataStok->lt, 0, 1);
                    } elseif ($dataStok) {
                        $lt = $dataStok->lt;
                    } else {
                        $lt = '0';
                    }

                    $standarDatang = $convertCreated->modify('+' . ($lt * 30) . ' days');
                    $standarDatangFormatted = $standarDatang->format('d-M-Y');

                    if ($convertDelivery <= now()) {
                        $monthDelivery = date(now()->format('m'));
                        $late = 'LATE';
                    } else {
                        $monthDelivery = $convertDelivery->format('m');
                        $late = 'ON PROCESS';
                    }

                    $ket_lt = ($convertDelivery <= $standarDatang) ? 'LEAD TIME' : 'NON LEAD TIME';

                    if (empty($i[3]) || $i[3] == 'NaN') {
                        $emptyPRItems[] = $i[1];
                    }

                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($i, $request){
                        return $value['item_number'] == $i[1] && $value['purchase_order'] == $i[0];
                    });

                    if($duplicateInArray){
                        $duplicatePO[] = $i[0];
                        $duplicateItems[] = $i[1];
                    } else {
                        $tempData[] = [
                            'purchase_order' => $i[0],
                            'item_number' => $i[1],
                            'product_name' => $i[2],
                            'purchase_requisition' => $i[3] ?? 'NaN',
                            'tpqty' => $i[4],
                            'tpunit' => $i[5],
                            'tpsite' => $i[6],
                            'tpvendor' => $i[7],
                            'delivery_date' => $convertDelivery,
                            'delivery_reminder' => $i[9],
                            'old_number_format' => $i[10],
                            'created_date_and_time' => $convertCreated,
                            'tpstatus' => $i[12],
                            'line_status' => $i[13],
                            'supplier_name' => $i[14],
                            'standar_datang' => $standarDatangFormatted,
                            'bulan_datang' => $monthDelivery,
                            'lt' => $lt,
                            'ket_late' => $late,
                            'ket_lt' => $ket_lt,
                            'date' => $request->date
                        ];
                    }
                }
            }
        }
        if (!empty($duplicateItems) && !empty($duplicatePO)) {
            $uniqueDuplicateItems = array_unique($duplicateItems);
            $uniqueDuplicatePO = array_unique($duplicatePO);
            $duplicateItemList = implode(', ', $uniqueDuplicateItems);
            $duplicatePOList = implode(', ', $uniqueDuplicatePO);
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal!',
                    'text' => "Terdapat duplikasi pada Item Number berikut: {$duplicateItemList} dengan Purchase Order berikut: {$duplicatePOList}."
                ]
            ]);
        }

        if($cekDate){
            foreach($tempData as $data){
                $itemInCekDate = collect($cekDate)->first(function ($value) use ($data) {
                    return $value['purchase_order'] === $data['purchase_order'] && $value['item_number'] === $data['item_number'];
                });
                if ($itemInCekDate) {
                    OpenPo::where([
                        ['purchase_order', '=', $data['purchase_order']],
                        ['item_number', '=', $data['item_number']]
                    ])->update($data);
                    $updatePO++;
                } else {
                    OpenPo::create($data);
                    $rowCountPo++;
                }
            }
        } else {
            foreach ($tempData as $data){
                OpenPo::create($data);
            }
            $rowCountPo++;
        }

        if (!empty($emptyPRItems)) {
            $emptyPRItemsList = implode(', ', $emptyPRItems);
            return back()->with([
                'swal' => [
                    'type' => 'warning',
                    'message' => 'Import Berhasil dengan catatan',
                    'text' => "Berhasil mengimpor {$rowCountPo} baris data Purchase Order dengan Purchase Requisiton {$emptyPRItemsList} yang blank."
                ]
            ]);
        } else if (!empty($emptyPRItems) && $updatePO > 0){
            $emptyPRItemsList = implode(', ', $emptyPRItems);
            return back()->with([
                'swal' => [
                    'type' => 'warning',
                    'message' => 'Berhasil Import dan Update data dengan Catatan',
                    'text' => "Berhasil mengimpor {$rowCountPo} baris data dan update {$updatePO} baris data dengan Purchase Requisition ($emptyPRItemsList} yang blank."
                ]
            ]);
        } else if ($updatePO > 0){
            return back()->with([
                'swal' => [
                    'type' => 'success',
                    'message' => 'Berhasil Import dan Update data dengan Catatan',
                    'text' => "Berhasil mengimpor {$rowCountPo} baris data dan update {$updatePO} baris data."
                ]
            ]);
        } else {
            return back()->with([
                'swal' => [
                    'type' => 'success',
                    'message' => 'Import Berhasil',
                    'text' => "Berhasil mengimpor {$rowCountPo} baris data."
                ]
            ]);
        }
    }

    public function export($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        return Excel::download(new PurchaseOrderExport($year, $month), 'Purchase Order '. $monthName . ' - ' . $year . '.xlsx');
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
        $validated = $request->validate([
            'delivery_reminder' => 'required',
            'id' => 'required'
        ]);
        $poData = OpenPo::findOrFail($id);
        $poData->update([
            'delivery_reminder' => $validated['delivery_reminder']
        ]);
        return response()->json([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Quantity Purchase Order berhasil diperbaharui'
            ]
        ]);
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
        DB::table('open_po')
            ->whereIn('id', $ids)
            ->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
