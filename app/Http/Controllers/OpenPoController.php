<?php

namespace App\Http\Controllers;

use App\Imports\PoImport;
use App\Models\OpenPo;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OpenPoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('open-po.index', [
            'title' => 'Open Po'
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/format-open-po.xlsx');
        return response()->download($filePath);
    }

    public function get_data()
    {
        $poData = OpenPo::select('*');
        return DataTables::of($poData)
            ->editColumn('purchase_order', function ($poData) {
                return $poData->purchase_order;
            })->editColumn('item_number', function ($poData) {
                return $poData->item_number;
            })->editColumn('product_name', function ($poData) {
                return $poData->product_name;
            })->editColumn('purchase_requisition', function ($poData) {
                return $poData->purchase_requisition;
            })->editColumn('supplier_name', function ($poData) {
                return $poData->supplier_name;
            })->editColumn('created_date_and_time', function ($poData) {
                return $poData->created_date_and_time;
            })->editColumn('delivery_date', function ($poData) {
                return $poData->delivery_date;
            })->editColumn('delivery_reminder', function ($poData) {
                return $poData->delivery_reminder;
            })->editColumn('line_status', function ($poData) {
                return $poData->line_status;
            })->editColumn('old_number_format', function ($poData) {
                return $poData->old_number_format;
            })->editColumn('lt', function ($poData) {
                return $poData->lt;
            })->editColumn('standar_datang', function ($poData) {
                return $poData->standar_datang;
            })->editColumn('bulan_datang', function ($poData) {
                return $poData->bulan_datang;
            })->editColumn('ket_late', function ($poData) {
                return $poData->ket_late;
            })->editColumn('ket_lt', function ($poData) {
                return $poData->ket_lt;
            })->editColumn('price', function ($poData) {
                return $poData->price;
            })->editColumn('amount', function ($poData) {
                return $poData->amount;
            })->rawColumns(['purchase_order', 'item_number', 'product_name', 'purchase_requisition', 'supplier_name', 'created_date_and_time', 'delivery_date', 'delivery_reminder', 'line_status', 'old_number_format', 'lt', 'standar_datang', 'bulan_datang', 'ket_late', 'ket_lt', 'price', 'amount'])->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx'
        ]);
        $import = new PoImport($import);
        $poVal = Excel::toArray($import, $request->file('file'));
        $emptyPRItems = [];
        $rowCountPo = 0;

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

                    // $ket_late = ($monthDelivery == date(now('m'))) ? 'LATE' : 'NOT LATE';
                    $ket_lt = ($convertDelivery <= $standarDatang) ? 'LEAD TIME' : 'NON LEAD TIME';

                    if (empty($i[3]) || $i[3] == 'NaN') {
                        $emptyPRItems[] = $i[1];
                    }

                    OpenPo::create([
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
                        'price' => round($i[15], 3),
                        'amount' => $i[15] * $i[9]
                    ]);
                    $rowCountPo++;
                }
            }
        }
        if (!empty($emptyPRItems)) {
            return back()->with([
                'status' => 'warning',
                'message' => 'Import berhasil dengan catatan',
                'emptyPRItems' => $emptyPRItems,
                'rowCountPo' => $rowCountPo
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Import berhasil',
            'rowCountPo' => $rowCountPo
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
