<?php

namespace App\Http\Controllers;

use App\Imports\BomImport;
use App\Models\Bom;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthBOM = Bom::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();
        $uniqueFgs = Bom::distinct('item_id_fgs')
            ->whereNotNull('item_id_fgs')
            ->pluck('item_id_fgs');
        return view('bom.index', [
            'title' => 'Index BOM',
            'monthBOM' => $monthBOM,
            'uniqueFgs' => $uniqueFgs
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/BOM.xlsx');
        return response()->download($filePath);
    }

    public function get_data(Request $request, $year, $month)
    {
        $bomData = Bom::whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
        return DataTables::of($bomData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);
        $dataArray = [];
        $bomVal = Excel::toArray(new Bom($dataArray), $request->file('file'));
        $rowCountBom = 0;

        foreach ($bomVal[0] as $key => $value) {
            if ($key > 0) {
                Bom::create([
                    'item_id_fgs' => $value[0],
                    'item_id_rmi' => $value[1],
                    'part_number' => $value[2],
                    'bomqty' => $value[3],
                    'unit_id' => $value[4]
                ]);
                $rowCountBom++;
            }
        }

        return back()->with([
            'swal' => [
                'type'  => 'success',
                'title' => 'Import Berhasil!',
                'text' => 'Data BOM berhasil ditambahkan. Total data: ' . $rowCountBom
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
    public function destroy(string $id)
    {
        //
    }
}
