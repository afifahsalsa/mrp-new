<?php

namespace App\Http\Controllers;

use App\Imports\BufferImport;
use App\Models\Buffer;
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
        return view('buffer.index', [
            'title' => 'Buffer'
        ]);
    }

    public function format_buffer()
    {
        $filePath = public_path('doc/format-buffer.xlsx');
        return response()->download($filePath);
    }

    public function get_data()
    {
        $bufferData = Buffer::select('*');
        return DataTables::of($bufferData)
            ->editColumn('item_number', function ($bufferData) {
                return $bufferData->item_number;
            })->editColumn('part_number', function ($bufferData) {
                return $bufferData->part_number;
            })->editColumn('product_name', function ($bufferData) {
                return $bufferData->product_name;
                // })->editColumn('usage', function ($bufferData) {
                //     return $bufferData->usage;
            })->editColumn('lt', function ($bufferData) {
                return $bufferData->lt;
            })->editColumn('supplier', function ($bufferData) {
                return $bufferData->supplier;
            })->editColumn('qty', function ($bufferData) {
                return $bufferData->qty;
            })->editColumn('date', function ($bufferData) {
                return $bufferData->date;
            })->rawColumns(['item_number', 'part_number', 'product_name', 'lt', 'supplier', 'qty', 'date'])->make(true);
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

        foreach ($bufferVal as $bv) {
            foreach ($bv as $idx => $i) {
                if ($idx > 0) {
                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($i, $request) {
                        return $value['item_number'] == $i[0] && $value['date'] == $request->date;
                    });

                    if($i[4] == null || $i[4] == '0' || $i[4] == 0){
                        $ltBlank[] = $i[4];
                    }

                    if ($duplicateInArray) {
                        $errorMessage = 'Terdapat duplikasi dengan item number ' . $i[0] . ' pada bulan ini.';
                        return back()->with([
                            'swal' => [
                                'type' => 'error',
                                'title' => 'Import Gagal',
                                'text' => $errorMessage,
                            ]
                        ]);
                    }

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

        foreach ($tempData as $data) {
            Buffer::create($data);
            $rowCountBuffer++;
        }
        $countLt = count($ltBlank);
        if($countLt > 0){
            return back()->with([
                'swal' => [
                    'type' => 'warning',
                    'title' => 'Import Berhasil dengan Catatan',
                    'text' => "Jumlah {$countLt} LT yang kosong.",
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

    public function choose_month(){
        return view('buffer.choose', [
            'title' => 'Choose Month Buffer'
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
