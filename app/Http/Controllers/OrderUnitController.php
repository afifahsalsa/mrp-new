<?php

namespace App\Http\Controllers;

use App\Models\OrderOriginal;
use App\Models\OrderUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderUnitData = OrderOriginal::join('production_planning', 'order_original.kodefgs', '=', 'production_planning.kodefgs')
            ->select(
                'order_original.kodefgs',
                'order_original.customer',
                'order_original.model',
                'order_original.partnumber',
                'order_original.kategori',
                DB::raw('CASE WHEN order_original.bulan_1 >= production_planning.bulan_1 THEN order_original.bulan_1 ELSE production_planning.bulan_1 END as bulan_1'),
                DB::raw('CASE WHEN order_original.bulan_2 >= production_planning.bulan_2 THEN order_original.bulan_2 ELSE production_planning.bulan_2 END as bulan_2'),
                DB::raw('CASE WHEN order_original.bulan_3 >= production_planning.bulan_3 THEN order_original.bulan_3 ELSE production_planning.bulan_3 END as bulan_3'),
                DB::raw('CASE WHEN order_original.bulan_4 >= production_planning.bulan_4 THEN order_original.bulan_4 ELSE production_planning.bulan_4 END as bulan_4'),
                DB::raw('CASE WHEN order_original.bulan_5 >= production_planning.bulan_5 THEN order_original.bulan_5 ELSE production_planning.bulan_5 END as bulan_5'),
                DB::raw('CASE WHEN order_original.bulan_6 >= production_planning.bulan_6 THEN order_original.bulan_6 ELSE production_planning.bulan_6 END as bulan_6'),
                DB::raw('CASE WHEN order_original.bulan_7 >= production_planning.bulan_7 THEN order_original.bulan_7 ELSE production_planning.bulan_7 END as bulan_7'),
                DB::raw('CASE WHEN order_original.bulan_8 >= production_planning.bulan_8 THEN order_original.bulan_8 ELSE production_planning.bulan_8 END as bulan_8'),
                DB::raw('CASE WHEN order_original.bulan_9 >= production_planning.bulan_9 THEN order_original.bulan_9 ELSE production_planning.bulan_9 END as bulan_9'),
                DB::raw('CASE WHEN order_original.bulan_10 >= production_planning.bulan_10 THEN order_original.bulan_10 ELSE production_planning.bulan_10 END as bulan_10'),
                DB::raw('CASE WHEN order_original.bulan_11 >= production_planning.bulan_11 THEN order_original.bulan_11 ELSE production_planning.bulan_11 END as bulan_11'),
                DB::raw('CASE WHEN order_original.bulan_12 >= production_planning.bulan_12 THEN order_original.bulan_12 ELSE production_planning.bulan_12 END as bulan_12'),
                DB::raw('CASE WHEN order_original.total >= production_planning.total THEN order_original.total ELSE production_planning.total END as total'),
                DB::raw('CASE WHEN order_original.average >= production_planning.average THEN order_original.average ELSE production_planning.average END as average')
            )->get();

        return view('mrp.order_unit', [
            'title' => 'Order In Unit',
            'orderUnitData' => $orderUnitData
        ]);
    }

    public function get_data()
    {
        $query = OrderOriginal::join('production_planning', 'order_original.kodefgs', '=', 'production_planning.kodefgs')
            ->select([
                'order_original.customer',
                'order_original.model',
                'order_original.kodefgs',
                'order_original.kategori',
                'order_original.partnumber',
                'order_original.bulan_1',
                'order_original.bulan_2',
                'order_original.bulan_3',
                'order_original.bulan_4',
                'order_original.bulan_5',
                'order_original.bulan_6',
                'order_original.bulan_7',
                'order_original.bulan_8',
                'order_original.bulan_9',
                'order_original.bulan_10',
                'order_original.bulan_11',
                'order_original.bulan_12',
                'order_original.total',
                'order_original.average',
                'order_original.bulan',
                'order_original.tahun'
            ]);
        return datatables($query)->make(true);
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
