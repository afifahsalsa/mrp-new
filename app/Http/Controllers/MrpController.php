<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\Buffer;
use App\Models\IncomingManual;
use App\Models\Moq;
use App\Models\Mpp;
use App\Models\OpenPo;
use App\Models\OpenPr;
use App\Models\Stok;
use App\Models\TempKebMaterial;
use App\Models\TempKebProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MrpController extends Controller
{
    // MRP
    public function index()
    {
        return view('mrp.index', [
            'title' => 'Index MRP'
        ]);
    }

    private function convertIntSatu($number, $base)
    {
        return floor(($number / $base) + 1) * $base;
    }

    private function convertInt($number, $base)
    {
        return floor(($number / $base)) * $base;
    }

    public function get_data(Request $request)
    {
        $selectedMonth = $request->get('selected_month', now()->format('Y-m'));
        $stoks = Stok::all();
        $result = [];

        foreach ($stoks as $stok) {
            $boms = Bom::where('item_id_rmi', $stok->item_number)->get();
            $moqMpqs = Moq::where('item_number', $stok->item_number)->get();
            $tempKebMaterials = TempKebMaterial::where('kode_rmi', $stok->item_number)->get();
            $buffers = Buffer::where('item_number', $stok->item_number)->get();
            $tempKebProduksis = TempKebProduksi::where('kode_rmi', $stok->item_number)->get();
            $openPos = OpenPo::where('item_number', $stok->item_number)->get();
            $openPrs = OpenPr::where('item_id', $stok->item_number)->get();
            $incomingManuals = IncomingManual::where('item_number', $stok->item_number)->get();

            if (
                $boms->isEmpty() || $moqMpqs->isEmpty() || $tempKebMaterials->isEmpty() ||
                $buffers->isEmpty() || $tempKebProduksis->isEmpty() || $openPos->isEmpty() ||
                $openPrs->isEmpty() || $incomingManuals->isEmpty()
            ) {
                continue;
            }

            foreach ($boms as $bom) {
                foreach ($moqMpqs as $moqMpq) {
                    foreach ($tempKebMaterials as $tempKebMaterial) {
                        foreach ($buffers as $buffer) {
                            foreach ($tempKebProduksis as $tempKebProduksi) {
                                $poS = 0;
                                foreach ($openPos as $openPo) {
                                    if ($openPo->item_number === $stok->item_number && $openPo->delivery_date < '2024-10-01') {
                                        $poS += $openPo->deliver_reminder;
                                    }
                                    foreach ($openPrs as $openPr) {
                                        foreach ($incomingManuals as $incomingManual) {
                                            // Hitung nilai balance_0
                                            $balance_0 = ($stok->stok ?? 0) - ($tempKebProduksi->keb_produksi ?? 0) +
                                                ($poS ?? 0) + ($openPr->qty ?? 0) - ($incomingManual->qty ?? 0);

                                            // Hitung nilai renc_beli
                                            $renc_beli = ($stok->stok ?? 0) - ($tempKebMaterial->keb_material ?? 0) -
                                                ($buffer->qty ?? 0) + ($poS ?? 0) + ($openPr->qty ?? 0) - ($incomingManual->qty ?? 0);
                                            if ($renc_beli < 0) {
                                                $renc_beli = abs($renc_beli);
                                            } else {
                                                $renc_beli = 0;
                                            }

                                            // Hitung MOQ + 1
                                            if ($renc_beli > 0) {
                                                $temp_moq_plus_1 = $this->convertIntSatu($renc_beli, $moqMpq->mpq);
                                            } else {
                                                $temp_moq_plus_1 = 0;
                                            }

                                            if ($temp_moq_plus_1 < $moqMpq->moq) {
                                                $moq_plus_1 = $moqMpq->moq;
                                            } else {
                                                if ($renc_beli > 0) {
                                                    $moq_plus_1 = $this->convertIntSatu($renc_beli, $moqMpq->mpq);
                                                } else {
                                                    $moq_plus_1 = 0;
                                                }
                                            }

                                            // Hitung MOQ
                                            if ($renc_beli > 0) {
                                                $temp_moq_0 = $this->convertInt($renc_beli, $moqMpq->mpq);
                                            } else {
                                                $temp_moq_0 = 0;
                                            }

                                            if ($temp_moq_0 < $moqMpq->moq) {
                                                $moq_0 = $moqMpq->moq;
                                            } else {
                                                if ($renc_beli > 0) {
                                                    $moq_0 = $this->convertInt($renc_beli, $moqMpq->mpq);
                                                } else {
                                                    $moq_0 = 0;
                                                }
                                            }

                                            //percentage buffer
                                            if ($balance_0 > 0) {
                                                $percent_buff = (round((($moq_plus_1 - $moq_0) / $buffer->qty), 3));
                                                // dd($balance_0, $percent_buff, $buffer->qty);
                                            } else {
                                                $percent_buff = 1;
                                            }

                                            // Pembelian_0
                                            if ($renc_beli > 0) {
                                                if ($percent_buff > 0.9) {
                                                    $pembelian_0 = $moq_0;
                                                } else {
                                                    $pembelian_0 = $moq_plus_1;
                                                }
                                            } else {
                                                $pembelian_0 = 0;
                                            }

                                            $result[] = [
                                                'item_number' => $stok->item_number,
                                                'part_number' => $stok->part_number,
                                                'product_name' => $stok->product_name,
                                                'spl' => $stok->spl,
                                                'lt' => $stok->lt,
                                                'li' => $stok->li,
                                                'type' => $stok->type,
                                                'stok' => $stok->stok,
                                                'unit_id' => $bom->unit_id ?? 0,
                                                'moq' => $moqMpq->moq ?? 0,
                                                'mpq' => $moqMpq->mpq ?? 0,
                                                'keb_material' => $tempKebMaterial->keb_material ?? 0,
                                                'buffer_qty' => $buffer->qty ?? 0,
                                                'keb_produksi' => $tempKebProduksi->keb_produksi ?? 0,
                                                'open_po_qty' => $poS ?? 0,
                                                'open_pr_qty' => $openPr->qty ?? 0,
                                                'incoming_manual_qty' => $incomingManual->qty ?? 0,
                                                'balance_0' => $balance_0,
                                                'renc_beli' => $renc_beli,
                                                'moq_plus_1' => $moq_plus_1,
                                                'moq_0' => $moq_0,
                                                'percent_buff' => $percent_buff,
                                                'pembelian_0' => $pembelian_0
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return DataTables::of($result)
            ->addIndexColumn()
            ->make(true);
    }

    // MOQ dan MPQ
    public function index_moq_mpq()
    {
        $monthMoq = Moq::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();
        return view('mrp.moq_mpq', [
            'title' => 'MOQ & MPQ',
            'monthMoq' => $monthMoq
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/MOQ & MPQ.xlsx');
        return response()->download($filePath);
    }

    public function get_data_moq($year, $month)
    {
        $moqData = Moq::whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
        return DataTables::of($moqData)->make(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        $moqVal = Excel::toArray(new Moq(), $request->file('file'));
        $tempData = [];
        $rowCountMOQ = 0;
        $duplicateItems = [];
        $updateMOQ = 0;

        $headers = [];
        foreach ($moqVal[0] as $key => $value) {
            if ($key === 0) {
                $headers = $value;
            } else {
                $data = array_combine($headers, $value);
                $duplicateInArray = collect($tempData)->contains(function ($value) use ($data) {
                    return $value['item_number'] == $data['ITEM NUMBER'] && $value['part_number'] == $data['PART NUMBER'];
                });

                if ($duplicateInArray) {
                    $duplicateItems[] = $data['ITEM NUMBER'];
                } else {
                    $tempData[] = [
                        'item_number' => $data['ITEM NUMBER'],
                        'part_number' => $data['PART NUMBER'],
                        'part_name' => $data['PART NAME'],
                        'lt' => $data['LEAD TIME'],
                        'spl' => $data['SUPPLIER'],
                        'li' => $data['L/I'],
                        'type' => $data['TYPE'],
                        'moq' => $data['MOQ'],
                        'mpq' => $data['MPQ'],
                        'created_at' => date(now())
                    ];
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
        foreach ($tempData as $data) {
            $cekDate = Moq::where(DB::raw("FORMAT(created_at, 'yyyy MM')"), '=', date('Y m', strtotime($data['created_at'])))->get();
            if ($cekDate) {
                $itemInCekDate = collect($cekDate)->first(function ($value) use ($data) {
                    return $value['item_number'] === $data['item_number'] && $value['part_number'] === $data['part_number'];
                });
                if ($itemInCekDate) {
                    Moq::where([
                        ['item_number', '=', $data['item_number']],
                        ['part_number', '=', $data['part_number']]
                    ])->update($data);
                    $updateMOQ++;
                } else {
                    Moq::create($data);
                    $rowCountMOQ++;
                }
            }
        }
        if ($updateMOQ > 0) {
            return back()->with([
                'swal' => [
                    'type' => 'success',
                    'title' => 'Import Berhasil!',
                    'text' => "Berhasil mengimpor {$rowCountMOQ} baris data dan mengupdate {$updateMOQ} baris data."
                ]
            ]);
        } else {
            return back()->with([
                'swal' => [
                    'type' => 'success',
                    'title' => 'Import Berhasil!',
                    'text' => "Berhasil mengimpor {$rowCountMOQ} baris data."
                ]
            ]);
        }
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'moq' => 'required|numeric|min:0',
            'mpq' => 'required|numeric|min:0',
            'id' => 'required'
        ]);

        $moq = Moq::findOrFail($id);
        $moq->update([
            'moq' => $validated['moq'],
            'mpq' => $validated['mpq'],
        ]);

        return response()->json([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'MOQ dan MPQ berhasil diperbarui!',
            ]
        ]);
    }

    // Kebutuhan Material
    public function index_keb_material(Request $request)
    {
        $tempKebMaterial = TempKebMaterial::all();
        if ($tempKebMaterial->isEmpty()) {
            $mppData = Mpp::all();
            foreach ($mppData as $data) {
                $fgsBom = Bom::where('item_id_fgs', $data->kodefgs)->get();
                if ($fgsBom->isNotEmpty()) {
                    foreach ($fgsBom as $bom) {
                        $keb_material = $bom->bomqty * $data->max_bulan_1;
                        TempKebMaterial::create([
                            'kode_fgs' => $data->kodefgs,
                            'kode_rmi' => $bom->item_id_rmi,
                            'keb_material' => $keb_material
                        ]);
                    }
                }
            }
        } else {
            foreach ($tempKebMaterial as $data) {
                $itemFgs = Mpp::where('kodefgs', $data->kode_fgs)->first();
                $itemRmi = Bom::where('item_id_rmi', $data->kode_rmi)
                    ->where('item_id_fgs', $data->kode_fgs)
                    ->first();
                if ($itemFgs && $itemRmi) {
                    $existingData = TempKebMaterial::where('kode_fgs', $data->kode_fgs)
                        ->where('kode_rmi', $data->kode_rmi)
                        ->first();
                    if ($existingData) {
                        $keb_material = $itemRmi->bomqty * $itemFgs->max_bulan_1;
                        $existingData->update([
                            'keb_material' => $keb_material
                        ]);
                    }
                }
            }
        }
        return view('mrp.keb_material', [
            'title' => 'Kebutuhan Material'
        ]);
    }

    public function get_data_keb_material()
    {
        $tempKebMaterial = TempKebMaterial::all();
        if ($tempKebMaterial->isEmpty()) {
            $mppData = Mpp::all();
            foreach ($mppData as $data) {
                $fgsBom = Bom::where('item_id_fgs', $data->kodefgs)->get();
                if ($fgsBom->isNotEmpty()) {
                    foreach ($fgsBom as $bom) {
                        $keb_material = $bom->bomqty * $data->max_bulan_1;
                        TempKebMaterial::create([
                            'kode_fgs' => $data->kodefgs,
                            'kode_rmi' => $bom->item_id_rmi,
                            'keb_material' => $keb_material
                        ]);
                    }
                }
            }
        } else {
            foreach ($tempKebMaterial as $data) {
                $itemFgs = Mpp::where('kodefgs', $data->kode_fgs)->first();
                $itemRmi = Bom::where('item_id_rmi', $data->kode_rmi)
                    ->where('item_id_fgs', $data->kode_fgs)
                    ->first();
                if ($itemFgs && $itemRmi) {
                    $existingData = TempKebMaterial::where('kode_fgs', $data->kode_fgs)
                        ->where('kode_rmi', $data->kode_rmi)
                        ->first();
                    if ($existingData) {
                        $keb_material = $itemRmi->bomqty * $itemFgs->max_bulan_1;
                        $existingData->update([
                            'keb_material' => $keb_material
                        ]);
                    }
                }
            }
        }
        return DataTables::of($tempKebMaterial)
            ->addIndexColumn()
            ->make(true);
    }

    // Kebutuhan Produksi
    public function index_keb_production()
    {
        $tempKebProduksi = TempKebProduksi::all();
        if ($tempKebProduksi->isEmpty()) {
            $mppData = Mpp::all();
            foreach ($mppData as $data) {
                $fgsBom = Bom::where('item_id_fgs', $data->kodefgs)->get();
                if ($fgsBom->isNotEmpty()) {
                    foreach ($fgsBom as $bom) {
                        $keb_produksi = $bom->bomqty * $data->prod_plan_bulan_1;
                        TempKebProduksi::create([
                            'kode_fgs' => $data->kodefgs,
                            'kode_rmi' => $bom->item_id_rmi,
                            'keb_produksi' => $keb_produksi
                        ]);
                    }
                }
            }
        } else {
            foreach ($tempKebProduksi as $data) {
                $itemFgs = Mpp::where('kodefgs', $data->kode_fgs)->first();
                $itemRmi = Bom::where('item_id_rmi', $data->kode_rmi)
                    ->where('item_id_fgs', $data->kode_fgs)
                    ->first();
                if ($itemFgs && $itemRmi) {
                    $existingData = TempKebProduksi::where('kode_fgs', $data->kode_fgs)
                        ->where('kode_rmi', $data->kode_rmi)
                        ->first();
                    if ($existingData) {
                        $keb_produksi = $itemRmi->bomqty * $itemFgs->prod_plan_bulan_1;
                        $existingData->update([
                            'keb_produksi' => $keb_produksi
                        ]);
                    }
                }
            }
        }
        return view('mrp.keb_production', [
            'title' => 'Kebutuhan Produksi'
        ]);
    }

    public function get_data_keb_production()
    {
        $query = TempKebProduksi::all();
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
}
