<?php

namespace App\Http\Controllers;

use App\Exports\BufferExport;
use App\Imports\BufferImport;
use App\Models\Buffer;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BufferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthBuffer = Buffer::selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('YEAR(date) DESC, MONTH(date) DESC')
            ->get();
        return view('buffer.choose', [
            'title' => 'Index Buffer',
            'monthBuffer' => $monthBuffer
        ]);
    }

    public function index_edit($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        return view('buffer.index', [
            'title' => 'Edit Buffer',
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/Buffer.xlsx');
        return response()->download($filePath);
    }

    public function get_unique_lt($year, $month)
    {
        $uniqueLTs = Buffer::whereYear('date', $year)
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
        $bufferData = Buffer::whereYear('date', $year)
            ->whereMonth('date', $month);
        if ($request->has('lt') && $request->lt !== '') {
            $bufferData = $bufferData->where('lt', $request->lt);
        }
        return DataTables::of($bufferData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlx,xlsx',
            'date' => 'required'
        ]);

        $bufferVal = Excel::toArray(new BufferImport([], $request->date), $request->file('file'));
        $tempData = [];
        $rowCountBuffer = 0;
        $rowCountUpdateBuff = 0;
        $ltBlank = [];
        $duplicateItems = [];
        $cekDate = Buffer::where(DB::raw("FORMAT(date, 'yyyy MM')"), '=', date('Y m', strtotime($request->date)))->get();

        $headers = [];
        foreach ($bufferVal as $bv) {
            foreach ($bv as $idx => $i) {
                if ($idx === 0) {
                    $headers = $i;
                    if($headers[0] !== 'ITEM NUMBER' || $headers[1] !== 'PART NUMBER' || $headers[2] !== 'PART NAME' || $headers[3] !== 'LT' || $headers[4] !== 'SPL' || $headers[5] !== 'L/I' || $headers[6] !== 'TYPE' || $headers[7] !== 'BUFFER QUANTITY') {
                        return back()->with([
                            'swal' => [
                                'type' => 'error',
                                'title' => 'Import Gagal',
                                'text' => 'Format file tidak sesuai',
                            ]
                        ]);
                    }
                } else {
                    $data = array_combine($headers, $i);
                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($data, $request) {
                        return $value['item_number'] == $data['ITEM NUMBER'] && $value['date'] == $request->date;
                    });

                    if (strtolower(substr($data['LT'], 1, 1)) === 'l') {
                        $lt = substr($data['LT'], 0, 1);
                    } else if (strtolower(substr($data['LT'], 1, 1)) === 'i'){
                        $lt = substr($data['LT'], 0, 1) + 1;
                    } else if ($data['LT'] === null) {
                        $ltBlank[] = $data['LT'];
                        $lt = '-';
                    } else {
                        $lt = $data['LT'];
                    }

                    if ($duplicateInArray) {
                        $duplicateItems[] = $data['ITEM NUMBER'];
                    } else {
                        $tempData[] = [
                            'item_number' => $data['ITEM NUMBER'],
                            'part_number' => $data['PART NUMBER'],
                            'part_name' => $data['PART NAME'],
                            'lt' => $lt,
                            'spl' => $data['SPL'],
                            'li' => $data['L/I'],
                            'type' => $data['TYPE'],
                            'qty' => intval($data['BUFFER QUANTITY']),
                            'date' => $request->date
                        ];
                    }
                }
            }
        }
        if (!empty($duplicateItems)) {
            $duplicateItems = array_unique($duplicateItems);
            $duplicateList = implode(', ', $duplicateItems);

            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal',
                    'text' => "Terdapat duplikasi pada item number berikut: {$duplicateList}.",
                ]
            ]);
        }
        if ($data['BUFFER QUANTITY'] === null) {
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Import Gagal!',
                    'text' => "Quantity harus diisi"
                ]
            ]);
        }
        if ($cekDate->isNotEmpty()) {
            foreach ($tempData as $data) {
                $itemInCekDate = collect($cekDate)->firstWhere('item_number', $data['item_number']);
                if ($itemInCekDate) {
                    Buffer::where('item_number', $data['item_number'])->update($data);
                    $rowCountUpdateBuff++;
                } else {
                    Buffer::create($data);
                    $rowCountBuffer++;
                }
            }
        } else {
            foreach ($tempData as $data) {
                Buffer::create($data);
                $rowCountBuffer++;
            }
        }
        $countLt = count($ltBlank);
        if ($countLt > 0) {
            return back()->with([
                'swal' => [
                    'type' => 'warning',
                    'title' => 'Import Berhasil dengan Catatan',
                    'text' => "Berhasil impor {$rowCountBuffer} baris data buffer dengan jumlah {$countLt} LT yang blanks.",
                ]
            ]);
        } else if($rowCountUpdateBuff > 0) {
            return back()->with([
                'swal' => [
                    'type' => 'success',
                    'title' => 'Import Berhasil',
                    'text' => "Berhasil mengimpor {$rowCountBuffer} baris data dan mengupdate {$rowCountUpdateBuff} baris data.",
                ]
            ]);
        } else {
            return back()->with([
                'swal' => [
                    'type' => 'success',
                    'title' => 'Import Berhasil',
                    'text' => "Berhasil mengimpor {$rowCountBuffer} baris data.",
                ]
            ]);
        }
    }

    public function export($year, $month)
    {
        return Excel::download(new BufferExport($year, $month), 'Buffer ' . now()->format('d-m-Y') . '.xlsx');
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
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'qty' => 'required|numeric|min:0',
            'id' => 'required'
        ]);
        $buffer = Buffer::findOrFail($id);
        $buffer->update([
            'qty' => $validated['qty'],
        ]);

        return response()->json([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Quantity berhasil diperbarui!',
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

        $itemNumbers = $request->item_numbers;

        $itemsInStok = DB::table('stok')
            ->whereIn('item_number', $itemNumbers)
            ->pluck('item_number')
            ->toArray();

        if (!empty($itemsInStok)) {
            return response()->json([
                'success' => false,
                'itemsInStok' => $itemsInStok,
                'message' => 'Tidak dapat menghapus karena item masih ada di stok'
            ], 400);
        }

        DB::table('buffer')
            ->whereIn('item_number', $itemNumbers)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
