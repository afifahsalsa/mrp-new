<?php

namespace App\Http\Controllers;

use App\Imports\PriceImport;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Currency;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthPrice = Price::selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('YEAR(date), MONTH(date) DESC')
            ->get();
        return view('price.choose', [
            'title' => 'Index Price',
            'monthPrice' => $monthPrice
        ]);
    }

    public function input_currency()
    {
        $currencyData = Price::whereYear('date', date('Y'))
            ->whereMonth('date', date('m'))
            ->pluck('currency')
            ->unique();
        return view('price.input_currency', [
            'title' => 'Input Currency',
            'currencyData' => $currencyData
        ]);
    }

    public function get_format()
    {
        $filePath = public_path('doc/Format Impor Price.xlsx');
        return response()->download($filePath);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx',
            'date' => 'required|date'
        ]);

        $priceVal = Excel::toArray(new PriceImport([], $request->date), $request->file('file'));
        $tempData = [];
        $rowCountPrice = 0;
        $duplicateItems = [];
        $cekDate = Price::where(DB::raw("FORMAT(date, 'yyyy MM')"), '=', date('Y m', strtotime($request->date)))->get();

        foreach ($priceVal as $pv) {
            foreach ($pv as $idx => $i) {
                if ($idx > 0) {
                    $duplicateInArray = collect($tempData)->contains(function ($value) use ($i, $request) {
                        return $value['item_id'] == $i[0] && $value['date'] == $request->date;
                    });

                    if ($duplicateInArray) {
                        $duplicateItems[] = $i[0];
                    } else {
                        $tempData[] = [
                            'item_id' => $i[0],
                            'category_item' => $i[1],
                            'part_name' => $i[2] ?? '-',
                            'part_number' => $i[3] ?? '-',
                            'search_name' => $i[4] ?? '-',
                            'satuan' => $i[5] ?? '-',
                            'price' => $i[6],
                            'currency' => $i[7],
                            'val_currency' => '-',
                            'price_idr' => '-',
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
                    'text' => "Terdapat duplikasi pada item id berikut: {$duplicateList}.",
                ]
            ]);
        }

        foreach ($tempData as $data) {
            if ($data['price'] === null) {
                return back()->with([
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Import Gagal!',
                        'text' => "<strong>Price</strong> harus diisi"
                    ]
                ]);
            }
            if ($data['currency'] === null) {
                return back()->with([
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Import Gagal!',
                        'text' => "<strong>Currency</strong> harus diisi"
                    ]
                ]);
            }
        }

        // $currencyData = collect($tempData)
        //     ->filter(function ($item) {
        //         return date('Y', strtotime($item['date'])) == date('Y') && date('m', strtotime($item['date'])) == date('m');
        //     })
        //     ->pluck('currency')
        //     ->unique();
        // dd($currencyData);

        if ($cekDate) {
            foreach ($tempData as $data) {
                $itemInCekDate = collect($cekDate)->firstWhere('item_id', $data['item_id']);
                if ($itemInCekDate) {
                    Price::where('item_id', $data['item_id'])->update($data);
                } else {
                    Price::create($data);
                }
                $rowCountPrice++;
            }
        } else {
            foreach ($tempData as $data) {
                Price::create($data);
                $rowCountPrice++;
            }
        }
        return redirect()->route('price.input-currency');
        // return back()->with([
        //     'swal' => [
        //         'type' => 'success',
        //         'title' => 'Import Berhasil',
        //         'text' => "Berhasil mengimpor {$rowCountPrice} baris data.",
        //     ]
        // ]);
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
    public function update(Request $request)
    {
        $request->validate([
            'currencies' => 'required|array',
            'currencies.*' => 'required|numeric|min:0',
        ]);
        foreach ($request->currencies as $currency => $value) {
            $result = preg_replace("/[^0-9]/", "", $value);
            $prices = Price::where('currency', $currency)->get();
            foreach ($prices as $price) {
                $price->update([
                    'val_currency' => $result,
                    'price_idr' => $price->price * $result,
                ]);
            }
        }

        return redirect()->route('price.index')->with([
            'swal' => [
                'type' => 'success',
                'title' => 'Import Berhasil!',
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
