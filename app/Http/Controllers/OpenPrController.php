<?php

namespace App\Http\Controllers;

use App\Imports\PrImport;
use App\Models\OpenPr;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OpenPrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthPR = OpenPr::selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('YEAR(date), MONTH(date) DESC')
            ->get();
        return view('open-pr.choose', [
            'title' => 'Index Open PR',
            'monthPR' => $monthPR
        ]);
    }

    public function index_edit($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        return view('open-pr.index', [
            'title' => 'Edit Open PR',
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/Format Impor Purchase Requisition.xlsx');
        return response()->download($filePath);
    }

    public function get_uniqe_status($year, $month)
    {
        $uniqueStatus = OpenPr::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->distinct('pr_status')
            ->pluck('pr_status')
            ->filter(function ($value) {
                return $value !== null;
            })
            ->values()
            ->toArray();
        return response()->json($uniqueStatus);
    }

    public function get_data(Request $request, $year, $month)
    {
        $prData = OpenPr::whereYear('date', $year)
            ->whereMonth('date', $month);
        if ($request->has('pr_status') && $request->pr_status !== '') {
            $prData = $prData->where('pr_status', $request->pr_status);
        }
        return DataTables::of($prData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx, csv',
            'date' => 'required'
        ]);
        $dataArray = [];
        $prVal = Excel::toArray(new PrImport($dataArray, $request->date), $request->file('file'));
        $rowCountPR = 0;
        $updatePR = 0;
        $duplicatePR = [];
        $duplicateItems = [];
        $tempData = [];
        $cekDate = OpenPr::where(DB::raw("FORMAT(date, 'yyyy MM')"), '=', date('Y m', strtotime($request->date)))->get();

        foreach ($prVal as $pr) {
            foreach ($pr as $idx => $i) {
                if ($idx > 0) {
                    $convertPrDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($i[4]);
                    $convertReqDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($i[5]);

                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($i, $request) {
                        return $value['pr_id'] == $i[0] && $value['item_id'] == $i[1] && $value['date'] == $request->date;
                    });

                    if ($i[7] != "IN REVIEW" && $i[7] !== "APPROVED") {
                        return back()->with([
                            'swal' => [
                                'type' => 'error',
                                'title' => 'Import Gagal',
                                'text' => "Kolom status harus berisi IN REVIEW atau APPROVED!"
                            ]
                        ]);
                    }

                    if ($duplicateInArray) {
                        $duplicatePR[] = $i[0];
                        $duplicateItems[] = $i[1];
                    } else {
                        $tempData[] = [
                            'pr_id' => $i[0],
                            'item_id' => $i[1],
                            'part' => $i[2],
                            'old_name' => $i[3],
                            'pr_date' => $convertPrDate,
                            'request_date' => $convertReqDate,
                            'qty' => intval($i[6]),
                            'pr_status' => $i[7],
                            'date' => $request->date
                        ];
                    }
                }
            }
        }

        if (!empty($duplicateItems) && !empty($duplicatePR)) {
            $uniqueDuplicateItems = array_unique($duplicateItems);
            $uniqueDuplicatePR = array_unique($duplicatePR);
            $duplicateItemList = implode(', ', $uniqueDuplicateItems);
            $duplicatePRList = implode(', ', $uniqueDuplicatePR);
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal!',
                    'text' => "Terdapat duplikasi data pada Item Number berikut: {$duplicateItemList} dengan Purchase Requisition  berikut: {$duplicatePRList}"
                ]
            ]);
        }

        if ($cekDate) {
            foreach ($tempData as $data) {
                $itemInCekDate = collect($cekDate)->first(function ($value) use ($data) {
                    return $value['pr_id'] === $data['pr_id'] && $value['item_id'] === $data['item_id'];
                });
                if ($itemInCekDate) {
                    OpenPr::where([
                        ['pr_id', '=', $data['pr_id']],
                        ['item_id', '=', $data['item_id']]
                    ])->update($data);
                    $updatePR++;
                } else {
                    OpenPr::create($data);
                    $rowCountPR++;
                }
            }
        }

        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'Import Berhasil!',
                'text' => "Berhasil mengimpor {$rowCountPR} baris data."
            ]
        ]);
    }

    public function export($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        // return Excel::download(new Purc)
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
            'qty' => 'required',
            'id' => 'required'
        ]);
        $poData = OpenPr::findOrFail($id);
        $poData->update([
            'qty' => $validated['qty']
        ]);
        return response()->json([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Quantity Purchase Requisition berhasil diperbaharui'
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
        DB::table('open_pr')
            ->whereIn('id', $ids)
            ->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
