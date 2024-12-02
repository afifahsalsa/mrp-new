<?php

namespace App\Http\Controllers;

use App\Exports\StokExport;
use App\Imports\StokImport;
use App\Models\Buffer;
use App\Models\Stok;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthStok = Stok::selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('YEAR(date), MONTH(date) DESC')
            ->get();
        return view('stok.choose', [
            'title' => 'Index Stok',
            'monthStok' => $monthStok
        ]);
    }

    public function index_edit($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        return view('stok.index', [
            'title' => 'Edit Stok',
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName
        ]);
    }

    public function index_view($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        return view('stok.view', [
            'title' => 'View Stok',
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName
        ]);
    }

    public function format_stok()
    {
        $filePath = public_path('doc/format-stok.xlsx');
        return response()->download($filePath);
    }

    public function get_unique_lt($year, $month)
    {
        $uniqueLTs = Stok::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->distinct('lt')
            ->pluck('lt')
            ->filter(function ($value) {
                return $value !== null;
            })
            ->values()
            ->toArray();

        return response()->json($uniqueLTs);
    }

    public function get_data(Request $request, $year, $month)
    {
        $stokData = Stok::whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($request->has('lt') && $request->lt !== '') {
            $stokData = $stokData->where('lt', $request->lt);
        }

        return DataTables::of($stokData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlx,xlsx',
            'date' => 'required'
        ]);

        $dataArray = [];
        $import = new StokImport($dataArray, $request->date);
        $stokVal = Excel::toArray($import, $request->file('file'));

        $month = date('m', strtotime($request->date));
        $year = date('Y', strtotime($request->date));

        $tempData = [];
        $duplicateItems = [];
        $ltBlank = [];
        $rowCountStok = 0;

        $cekDate = Stok::whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [$year, $month])->get();

        foreach ($stokVal as $sv) {
            foreach ($sv as $idx => $i) {
                if ($idx === 0) continue;

                $cekItem = Buffer::where('item_number', $i[0])->first();
                if (!$cekItem) {
                    return back()->with([
                        'status' => 'error',
                        'message' => "Item Number {$i[0]} tidak ditemukan pada database Buffer"
                    ]);
                }

                $duplicateInArray = collect($tempData)->contains(function ($value) use ($i, $request) {
                    return $value['item_number'] == $i[0] && $value['date'] == $request->date;
                });

                if ($duplicateInArray) {
                    $duplicateItems[] = $i[0];
                    continue;
                }

                $itemData = [
                    'buffer_id' => $cekItem->id,
                    'item_number' => $i[0],
                    'part_number' => $i[1],
                    'product_name' => $i[2],
                    'lt' => $i[3] ?? '-',
                    'li' => $i[5],
                    'stok' => intval($i[4]),
                    'qty_buffer' => $cekItem->qty,
                    'percentage' => $cekItem->qty > 0 ? intval((intval($i[4]) / intval($cekItem->qty)) * 100) : 0,
                    'date' => $request->date
                ];

                if ($itemData['lt'] === '-') {
                    $ltBlank[] = $i[0];
                }

                $tempData[] = $itemData;
            }
        }

        if (!empty($duplicateItems)) {
            $duplicateItems = array_unique($duplicateItems);
            $duplicateList = implode(', ', $duplicateItems);
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal!',
                    'text' => "Terdapat duplikasi pada item number berikut: {$duplicateList}. Silakan periksa kembali file Anda.",
                ]
            ]);
        }

        $importResult = DB::transaction(function () use ($tempData, $cekDate, &$rowCountStok) {
            foreach ($tempData as $data) {
                $existingRecord = $cekDate->firstWhere('item_number', $data['item_number']);

                if ($existingRecord) {
                    Stok::where('item_number', $data['item_number'])
                        ->whereRaw("YEAR(date) = ? AND MONTH(date) = ?", [
                            date('Y', strtotime($data['date'])),
                            date('m', strtotime($data['date']))
                        ])
                        ->update($data);
                } else {
                    Stok::create($data);
                }
                $rowCountStok++;
            }
            return $rowCountStok;
        });

        $countLt = count($ltBlank);
        $messageType = $countLt > 0 ? 'warning' : 'success';
        $messageText = $countLt > 0
            ? "Berhasil impor {$importResult} baris data stok dengan jumlah {$countLt} LT yang blank."
            : "Berhasil mengimpor {$importResult} baris data.";

        return redirect()->route('stok.view', ['year' => $year, 'month' => $month])->with([
            'swal' => [
                'type' => $messageType,
                'title' => $countLt > 0 ? 'Import Berhasil dengan Catatan' : 'Import Berhasil!',
                'text' => $messageText
            ]
        ]);
    }

    public function export($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        return Excel::download(new StokExport($year, $month), 'Stok ' . $monthName .' - '. $year . '.xlsx');
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
            'stok' => 'required',
            'id' => 'required'
        ]);
        $stok = Stok::findOrFail($id);
        if($stok->qty_buffer == 0){
            $percentage = 0;
        } else {
            $percentage = intval($stok->stok) / intval($stok->qty_buffer) * 100;
        }
        $stok->update([
            'stok' => $validated['stok'],
            'percentage' =>  intval($percentage)
        ]);
        return response()->json([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Stok berhasil diperbaharui!'
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'item_numbers' => 'required|array',
            'item_numbers.*' => 'required|string'
        ]);

        // Ambil item numbers yang akan dihapus
        $itemNumbers = $request->item_numbers;

        // Hapus data berdasarkan item_numbers yang dipilih
        DB::table('stok')
            ->whereIn('item_number', $itemNumbers)
            ->delete();

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
