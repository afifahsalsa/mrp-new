<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use DateTime;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sales::all();
        $monthNumbers = [];
        foreach ($sales as $sale){
            $monthNumbers[$sale->id] = date('m', strtotime($sale->bulan));
        }
        return view('sales.index', [
            'title' => 'Index Sales',
            'sales' => $sales,
            'monthNumbers' => $monthNumbers
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
        if ($request->qty === null){
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Isi quantity terlebih dahulu!'
                ]
            ]);
        }

        $this->validate($request, [
            'bulan' => 'required',
            'qty' => 'required'
        ]);

        $year = explode('-', $request->bulan)[0];
        $month = explode('-', $request->bulan)[1];
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        Sales::create([
            'bulan' => $monthName,
            'tahun' => $year,
            'qty' => $request->qty
        ]);

        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil menambahkan quantity sales!'
            ]
        ]);
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
        $sale = Sales::findOrFail($id);
        return view('sales.edit', compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'bulan' => 'required',
            'qty' => 'required'
        ]);

        $year = explode('-', $request->bulan)[0];
        $month = explode('-', $request->bulan)[1];
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');

        $sale = Sales::findOrFail($id);
        $sale->update([
            'bulan' => $monthName,
            'tahun' => $year,
            'qty' => $request->qty
        ]);

        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil mengupdate quantity sales!'
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = Sales::findOrFail($id);
        $sale->delete();

        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil menghapus quantity sales!'
            ]
        ]);
    }
}
