<?php

namespace App\Http\Controllers;

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

    public function index_edit($year, $month){
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        return view('stok.index', [
            'title' => 'Edit Stok',
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName
        ]);
    }

    public function index_view($year, $month){
        return view('stok.view', [
            'title' => 'View Stok',
            'year' => $year,
            'month' => $month
        ]);
    }

    public function format_stok()
    {
        $filePath = public_path('doc/format-stok.xlsx');
        return response()->download($filePath);
    }

    public function get_data($year, $month)
    {
        $stokData = Stok::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->select('*');
        return DataTables::of($stokData)
            ->editColumn('item_number', function ($stokData) {
                return $stokData->item_number;
            })->editColumn('part_number', function ($stokData) {
                return $stokData->part_number;
            })->editColumn('product_name', function ($stokData) {
                return $stokData->product_name;
            })->editColumn('lt', function ($stokData) {
                return $stokData->lt;
            })->editColumn('li', function ($stokData) {
                return $stokData->li;
            })->editColumn('stok', function ($stokData) {
                return $stokData->stok;
            })->editColumn('qty_buffer', function ($stokData) {
                return $stokData->qty_buffer;
            })->editColumn('percentage', function ($stokData) {
                return $stokData->percentage;
            })->editColumn('date', function ($stokData) {
                return $stokData->date;
            })->rawColumns(['item_number', 'part_number', 'product_name','lt', 'li', 'stok', 'qty_buffer', 'percentage', 'date'])->make(true);
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
        $rowCountStok = 0;

        foreach ($stokVal as $sv) {
            foreach ($sv as $idx => $i) {
                if ($idx > 0) {
                    $cekItem = Buffer::where('item_number', $i[0])->first();
                    if(!$cekItem){
                        return back()->with([
                            'status' => 'error',
                            'message' => 'Item Number Stok tidak ditemukan pada database Buffer'
                        ]);
                    }
                    $monthItem = date('m', strtotime($cekItem->date));

                    if($i[3] == null){
                        $lt = '0';
                    } else {
                        $lt = $i[3];
                    }

                    if ($cekItem && $month == $monthItem) {
                        if($cekItem->qty == 0 || $cekItem->qty == '0'){
                            Stok::create([
                                'buffer_id' => $cekItem->id,
                                'item_number' => $i[0],
                                'part_number' => $i[1],
                                'product_name' => $i[2],
                                'lt' => $lt,
                                'li' => $i[5],
                                'stok' => intval($i[4]),
                                'qty_buffer' => $cekItem->qty,
                                'percentage' => 0,
                                'date' => $request->date
                            ]);
                        } else{
                            $percentage = ($i[4] / $cekItem->qty) * 100;
                            Stok::create([
                                'buffer_id' => $cekItem->id,
                                'item_number' => $i[0],
                                'part_number' => $i[1],
                                'product_name' => $i[2],
                                'lt' => $lt,
                                'li' => $i[5],
                                'stok' => intval($i[4]),
                                'qty_buffer' => $cekItem->qty,
                                'percentage' =>  intval($percentage),
                                'date' => $request->date
                            ]);
                        }
                    } else if ($cekItem) {
                        if($cekItem->qty == 0 || $cekItem->qty == '0'){
                            Stok::create([
                                'buffer_id' => $cekItem->id,
                                'item_number' => $i[0],
                                'part_number' => $i[1],
                                'product_name' => $i[2],
                                'lt' => $lt,
                                'li' => $i[5],
                                'stok' => intval($i[4]),
                                'qty_buffer' => $cekItem->qty,
                                'percentage' => 0,
                                'date' => $request->date
                            ]);
                        } else{
                            $percentage = round(($i[4] / $cekItem->qty) * 100, 2);
                            Stok::create([
                                'buffer_id' => $cekItem->id,
                                'item_number' => $i[0],
                                'part_number' => $i[1],
                                'product_name' => $i[2],
                                'lt' => $lt,
                                'li' => $i[5],
                                'stok' => intval($i[4]),
                                'qty_buffer' => $cekItem->qty,
                                'percentage' => intval($percentage),
                                'date' => $request->date
                            ]);
                        }
                    } else {
                        Stok::create([
                            'buffer_id' => null,
                            'item_number' => $i[0],
                            'part_number' => $i[1],
                            'product_name' => $i[2],
                            'lt' => $lt,
                            'li' => $i[5],
                            'stok' => intval($i[4]),
                            'qty_buffer' => null,
                            'percentage' => null,
                            'date' => $request->date
                        ]);
                    }
                    $rowCountStok++;
                }
            }
        }
        return back()->with([
            'status' => 'success',
            'message' => 'Import Sukses!!!',
            'rowCountStok' => $rowCountStok
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
        $validated = $request->validate([
            'stok' => 'required',
            'id' => 'required'
        ]);
        $stok = Stok::findOrFail($id);
        $percentage = $stok->stok / $stok->qty_buffer;
        $stok->update([
            'stok' => $validated['stok'],
            'percentage' => $percentage
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
