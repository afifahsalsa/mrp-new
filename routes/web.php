<?php

use App\Http\Controllers\BomController;
use App\Http\Controllers\BufferController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncomingManualController;
use App\Http\Controllers\IncomingNonManualController;
use App\Http\Controllers\MppController;
use App\Http\Controllers\MrpController;
use App\Http\Controllers\OpenPoController;
use App\Http\Controllers\OpenPrController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisualizationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard');
// });
Auth::routes();
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

// Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
Route::get('/dashboard/data', [HomeController::class, 'getChartData'])->name('dashboard.data');

Route::middleware(['auth', 'role:admin,staff,superuser'])->group(function () {
    Route::get('visualization-buffer-stock', [VisualizationController::class, 'bufferStokChart'])->name('buffer.stok.visualisasi');
    Route::prefix('/buffer')->group(function () {
        Route::get('/', [BufferController::class, 'index'])->name('buffer.index');
        Route::get('format-import', [BufferController::class, 'get_format'])->name('buffer.format-import');
        Route::get('load-data/{year}/{month}', [BufferController::class, 'get_data'])->name('buffer.data');
        Route::post('import', [BufferController::class, 'import'])->name('buffer.import');
        Route::get('get-unique-lt/{year}/{month}', [BufferController::class, 'get_unique_lt'])->name('buffer.unique-lt');
        Route::put('update/{id}', [BufferController::class, 'update'])->name('buffer.update');
    });
    Route::prefix('/stok')->group(function () {
        Route::get('/', [StokController::class, 'index'])->name('stok.index');
        Route::get('format-import', [StokController::class, 'get_format'])->name('stok.format-import');
        Route::get('load-data/{year}/{month}', [StokController::class, 'get_data'])->name('stok.data');
        Route::put('update/{id}', [StokController::class, 'update'])->name('stok.update');
        Route::post('import', [StokController::class, 'import'])->name('stok.import');
        Route::get('get-unique-lt/{year}/{month}', [StokController::class, 'get_unique_lt'])->name('stok.unique-lt');
    });
    Route::prefix('/purchase-order')->group(function () {
        Route::get('/', [OpenPoController::class, 'index'])->name('open-po.index');
        Route::put('update/{id}', [OpenPoController::class, 'update'])->name('open-po.update');
        Route::get('format-import', [OpenPoController::class, 'get_format'])->name('open-po.format');
        Route::get('load-data/{year}/{month}', [OpenPoController::class, 'get_data'])->name('open-po.data');
        Route::post('import', [OpenPoController::class, 'import'])->name('open-po.import');
        Route::get('get-unique-po/{year}/{month}', [OpenPoController::class, 'get_unique_po'])->name('open-po.unique-po');
    });
    Route::prefix('/purchase-requisition')->group(function () {
        Route::get('/', [OpenPrController::class, 'index'])->name('open-pr.index');
        Route::put('update/{id}', [OpenPrController::class, 'update'])->name('open-pr.update');
        Route::get('format-import', [OpenPrController::class, 'get_format'])->name('open-pr.format');
        Route::get('load-data/{year}/{month}', [OpenPrController::class, 'get_data'])->name('open-pr.data');
        Route::post('import', [OpenPrController::class, 'import'])->name('open-pr.import');
        Route::get('get-unique-status/{year}/{month}', [OpenPrController::class, 'get_uniqe_status'])->name('open-pr.unique-status');
    });
    Route::prefix('/mpp')->group(function () {
        Route::prefix('/order-original')->group(function () {
            Route::get('/', [MppController::class, 'index_choose_order_customer'])->name('order-customer.index');
            Route::get('format-import', [MppController::class, 'get_format'])->name('mpp.format');
            Route::post('import', [MppController::class, 'import'])->name('mpp.import');
            Route::get('load-data/{year}/{month}', [MppController::class, 'get_data'])->name('mpp.data');
        });
        Route::prefix('/production-planning')->group(function () {
            Route::get('/', [MppController::class, 'index_choose_prod_plan'])->name('prod-plan.index');
        });
        Route::prefix('/order-unit')->group(function () {
            Route::get('/', [MppController::class, 'index_choose_max_unit'])->name('max.index');
        });
        Route::prefix('/incoming-manual')->group(function () {
            Route::get('/', [IncomingManualController::class, 'index'])->name('incomong-manual.index');
            Route::get('format-import', [IncomingManualController::class, 'get_format'])->name('incoming-manual.format');
            Route::post('import', [IncomingManualController::class, 'import'])->name('incoming-manual.import');
            Route::get('load-data/{year}/{month}', [IncomingManualController::class, 'get_data'])->name('incoming-manual.data');
        });
    });
    Route::prefix('/incoming-non-manual')->group(function () {
        Route::get('/', [IncomingNonManualController::class, 'index'])->name('incoming-non-manual.index');
        Route::get('format-import', [IncomingNonManualController::class, 'get_format'])->name('incoming-non-manual.format');
        Route::post('import', [IncomingNonManualController::class, 'import'])->name('incoming-non-manual.import');
        Route::get('load-data/{year}/{month}', [IncomingNonManualController::class, 'get_data'])->name('incoming-non-manual.data');
    });
    Route::prefix('sales')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('sales.index');
        Route::post('create', [SalesController::class, 'store'])->name('sales.store');
        Route::put('edit/{id}', [SalesController::class, 'update'])->name('sales.update');
        Route::delete('delete/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');
    });
    Route::prefix('bom')->group(function(){
        route::get('/', [BomController::class, 'index'])->name('bom.index');
        route::get('format-import', [BomController::class, 'get_format'])->name('bom.format');
        route::post('import', [BomController::class, 'import'])->name('bom.import');
        Route::get('load-data/{year}/{month}', [BomController::class, 'get_data'])->name('bom.data');
    });
    Route::prefix('mrp')->group(function(){
        Route::get('/', [MrpController::class, 'index'])->name('mrp.index');
        Route::get('load-data', [MrpController::class, 'get_data'])->name('mrp.data');
        Route::prefix('keb-material')->group(function(){
            Route::get('/', [MrpController::class, 'index_keb_material'])->name('mrp.keb-material');
            Route::get('load-data-keb-material', [MrpController::class, 'get_data_keb_material'])->name('mrp.data-keb-material');
        });
        Route::prefix('keb-production')->group(function(){
            Route::get('/', [MrpController::class, 'index_keb_production'])->name('mrp.keb-production');
            Route::get('load-data-keb-production', [MrpController::class, 'get_data_keb_production'])->name('mrp.data-keb-production');
        });
        Route::prefix('/moq-mpq')->group(function () {
            Route::get('/', [MrpController::class, 'index_moq_mpq'])->name('mrp.moq-mpq');
            Route::get('format-import', [MrpController::class, 'get_format'])->name('mrp.format');
            Route::post('import', [MrpController::class, 'import'])->name('mrp.import');
            Route::get('load-data/{year}/{month}', [MrpController::class, 'get_data_moq'])->name('mrp.data-moq');
            Route::put('update/{id}', [MrpController::class, 'update'])->name('mrp.update');
        });
    });
    Route::prefix('/price')->group(function () {
        Route::get('/', [PriceController::class, 'index'])->name('price.index');
        Route::get('format-import', [PriceController::class, 'get_format'])->name('price.format');
        Route::post('import', [PriceController::class, 'import'])->name('price.import');
        Route::get('/input-currency', [PriceController::class, 'input_currency'])->name('price.input-currency');
        Route::post('update-currency', [PriceController::class, 'update'])->name('price.update-currency');
        Route::get('load-data/{year}/{month}', [PriceController::class, 'get_data'])->name('price.data');
        Route::get('get-unique-currency/{year}/{month}', [PriceController::class, 'get_unique_currency'])->name('price.unique-currency');
    });
});
Route::middleware(['auth', 'role:staff,superuser'])->group(function () {
    Route::prefix('/buffer')->group(function () {
        Route::get('edit/{year}/{month}', [BufferController::class, 'index_edit'])->name('buffer.index-edit');
        Route::put('update/{id}', [BufferController::class, 'update'])->name('buffer.update');
        Route::delete('delete', [BufferController::class, 'destroy'])->name('buffer.delete');
    });
    Route::prefix('/stok')->group(function () {
        Route::get('export/{year}/{month}', [StokController::class, 'export'])->name('stok.export');
        Route::delete('delete', [StokController::class, 'destroy'])->name('stok.delete');
        Route::get('edit/{year}/{month}', [StokController::class, 'index_edit'])->name('stok.index-edit');
    });
    Route::prefix('/purchase-order')->group(function () {
        Route::get('export/{year}/{month}', [OpenPoController::class, 'export'])->name('open-po.export');
        Route::delete('delete', [OpenPoController::class, 'destroy'])->name('open-po.delete');
        Route::get('edit/{year}/{month}', [OpenPoController::class, 'index_edit'])->name('open-po.index-edit');
    });
    Route::prefix('/purchase-requisition')->group(function () {
        Route::get('edit/{year}/{month}', [OpenPrController::class, 'index_edit'])->name('open-pr.index-edit');
        Route::delete('delete', [OpenPrController::class, 'destroy'])->name('open-pr.delete');
    });
    Route::prefix('/mpp')->group(function () {
        Route::prefix('/order-original')->group(function () {
            Route::delete('delete', [MppController::class, 'destroy'])->name('mpp.delete');
        });
        Route::prefix('/incoming-manual')->group(function () {
            Route::delete('delete', [IncomingManualController::class, 'destroy'])->name('incoming-manual.delete');
        });
    });
    Route::prefix('/incoming-non-manual')->group(function () {
        Route::delete('delete', [IncomingNonManualController::class, 'destroy'])->name('incoming-non-manual.delete');
    });
    Route::resource('sales', SalesController::class);
    Route::prefix('/price')->group(function () {
        Route::get('/', [PriceController::class, 'index'])->name('price.index');
        Route::get('format-import', [PriceController::class, 'get_format'])->name('price.format');
        Route::post('import', [PriceController::class, 'import'])->name('price.import');
        Route::get('/input-currency', [PriceController::class, 'input_currency'])->name('price.input-currency');
        Route::post('update-currency', [PriceController::class, 'update'])->name('price.update-currency');
        Route::get('load-data/{year}/{month}', [PriceController::class, 'get_data'])->name('price.data');
        Route::get('get-unique-currency/{year}/{month}', [PriceController::class, 'get_unique_currency'])->name('price.unique-currency');
        Route::delete('delete', [PriceController::class, 'destroy'])->name('price.delete');
    });
});
Route::middleware(['role:superuser'])->group(function () {
    Route::resource('user', UserController::class);
});
