<?php

namespace App\Http\Controllers;

use App\Exports\BufferExport;
use App\Imports\BufferImport;
use App\Models\Buffer;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
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

    public function format_buffer()
    {
        $filePath = public_path('doc/format-buffer.xlsx');
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
        $ltBlank = [];
        $duplicateItems = [];
        $cekDate = Buffer::where(DB::raw("FORMAT(date, 'yyyy MM')"), '=', date('Y m', strtotime($request->date)))->get();

        foreach ($bufferVal as $bv) {
            foreach ($bv as $idx => $i) {
                if ($idx > 0) {
                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($i, $request) {
                        return $value['item_number'] == $i[0] && $value['date'] == $request->date;
                    });

                    if ($duplicateInArray) {
                        $duplicateItems[] = $i[0];
                    } else if ($i[4] === null) {
                        $ltBlank[] = $i[4];
                        $tempData[] = [
                            'item_number' => $i[0],
                            'part_number' => $i[1],
                            'product_name' => $i[2],
                            'usage' => $i[3],
                            'lt' => '-',
                            'supplier' => $i[5],
                            'qty' => intval($i[6]),
                            'date' => $request->date
                        ];
                    } else {
                        $tempData[] = [
                            'item_number' => $i[0],
                            'part_number' => $i[1],
                            'product_name' => $i[2],
                            'usage' => $i[3],
                            'lt' => $i[4],
                            'supplier' => $i[5],
                            'qty' => intval($i[6]),
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

        if ($cekDate) {
            foreach ($tempData as $data) {
                $itemInCekDate = collect($cekDate)->firstWhere('item_number', $data['item_number']);
                if ($itemInCekDate) {
                    Buffer::where('item_number', $data['item_number'])->update($data);
                } else {
                    Buffer::create($data);
                }
                $rowCountBuffer++;
            }
        } else {
            foreach ($tempData as $data) {
                Buffer::create($data);
                $rowCountBuffer++;
            }
        }

        $year = date('Y', strtotime($request->date));
        $month = date('m', strtotime($request->date));
        $countLt = count($ltBlank);
        if ($countLt > 0) {
            return redirect()->route('buffer.view', ['year' => $year, 'month' => $month])->with([
                'swal' => [
                    'type' => 'warning',
                    'title' => 'Import Berhasil dengan Catatan',
                    'text' => "Berhasil impor {$rowCountBuffer} baris data buffer dengan jumlah {$countLt} LT yang blanks.",
                ]
            ]);
        } else {
            return redirect()->route('buffer.view', ['year' => $year, 'month' => $month])->with([
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
