<?php

use App\Http\Controllers\BufferController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MppController;
use App\Http\Controllers\OpenPoController;
use App\Http\Controllers\OpenPrController;
use App\Http\Controllers\OrderOriginalController;
use App\Http\Controllers\OrderUnitController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\StokController;
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
Route::prefix('/ppic')->group(function () {
    Route::get('visualization-buffer-stock', [VisualizationController::class, 'bufferStokChart'])->name('buffer.stok.visualisasi');
    Route::prefix('/buffer')->group(function () {
        Route::get('/', [BufferController::class, 'index'])->name('buffer.index');
        Route::get('edit/{year}/{month}', [BufferController::class, 'index_edit'])->name('buffer.index-edit');
        Route::get('view/{year}/{month}', [BufferController::class, 'index_view'])->name('buffer.view');
        Route::get('format-import', [BufferController::class, 'format_buffer'])->name('buffer.format-import');
        Route::get('load-data/{year}/{month}', [BufferController::class, 'get_data'])->name('buffer.data');
        Route::put('update/{id}', [BufferController::class, 'update'])->name('buffer.update');
        Route::post('import', [BufferController::class, 'import'])->name('buffer.import');
        Route::get('export/{year}/{month}', [BufferController::class, 'export'])->name('buffer.export');
        Route::delete('delete', [BufferController::class, 'destroy'])->name('buffer.delete');
        Route::get('get-unique-lt/{year}/{month}', [BufferController::class, 'get_unique_lt'])->name('buffer.unique-lt');
    });
    Route::prefix('/stok')->group(function(){
        Route::get('/', [StokController::class, 'index'])->name('stok.index');
        Route::get('edit/{year}/{month}', [StokController::class, 'index_edit'])->name('stok.index-edit');
        Route::get('view/{year}/{month}', [StokController::class, 'index_view'])->name('stok.view');
        Route::get('format-import', [StokController::class, 'format_stok'])->name('stok.format');
        Route::get('load-data/{year}/{month}', [StokController::class, 'get_data'])->name('stok.data');
        Route::put('update/{id}', [StokController::class, 'update'])->name('stok.update');
        Route::post('import', [StokController::class, 'import'])->name('stok.import');
        Route::get('export/{year}/{month}', [StokController::class, 'export'])->name('stok.export');
        Route::delete('delete', [StokController::class, 'destroy'])->name('stok.delete');
        Route::get('get-unique-lt/{year}/{month}', [StokController::class, 'get_unique_lt'])->name('stok.unique-lt');
    });
    Route::prefix('/purchase-order')->group(function(){
        Route::get('/', [OpenPoController::class, 'index'])->name('open-po.index');
        Route::get('edit/{year}/{month}', [OpenPoController::class, 'index_edit'])->name('open-po.index-edit');
        Route::put('update/{id}', [OpenPoController::class, 'update'])->name('open-po.update');
        Route::get('format-import', [OpenPoController::class, 'get_format'])->name('open-po.format');
        Route::get('load-data/{year}/{month}', [OpenPoController::class, 'get_data'])->name('open-po.data');
        Route::post('import', [OpenPoController::class, 'import'])->name('open-po.import');
        Route::get('export/{year}/{month}', [OpenPoController::class, 'export'])->name('open-po.export');
        Route::delete('delete', [OpenPoController::class, 'destroy'])->name('open-po.delete');
        Route::get('get-unique-po/{year}/{month}', [OpenPoController::class, 'get_unique_po'])->name('open-po.unique-po');
    });
    Route::prefix('/purchase-requisition')->group(function(){
        Route::get('/', [OpenPrController::class, 'index'])->name('open-pr.index');
        Route::get('edit/{year}/{month}', [OpenPrController::class, 'index_edit'])->name('open-pr.index-edit');
        Route::put('update/{id}', [OpenPrController::class, 'update'])->name('open-pr.update');
        Route::get('format-import', [OpenPrController::class, 'get_format'])->name('open-pr.format');
        Route::get('load-data/{year}/{month}', [OpenPrController::class, 'get_data'])->name('open-pr.data');
        Route::post('import', [OpenPrController::class, 'import'])->name('open-pr.import');
        Route::delete('delete', [OpenPrController::class, 'destroy'])->name('open-pr.delete');
        Route::get('get-unique-status/{year}/{month}', [OpenPrController::class, 'get_uniqe_status'])->name('open-pr.unique-status');
    });
    Route::prefix('/mpp')->group(function(){
        Route::prefix('/order-original')->group(function(){
            Route::get('/', [MppController::class, 'index_choose_order_customer'])->name('order-customer.index');
            Route::get('format-import', [MppController::class, 'get_format'])->name('mpp.format');
            Route::post('import', [MppController::class, 'import'])->name('mpp.import');
            Route::get('load-data/{year}/{month}', [MppController::class, 'get_data'])->name('mpp.data');
        });
        Route::prefix('/production-planning')->group(function(){
            Route::get('/', [MppController::class, 'index_choose_prod_plan'])->name('prod-plan.index');
            Route::get('load-data', [PlanningController::class, 'get_data'])->name('planning-production.data');
            Route::post('import', [PlanningController::class, 'import'])->name('planning-production.import');
        });
        Route::prefix('/order-unit')->group(function(){
            Route::get('/', [MppController::class, 'index_choose_max_unit'])->name('max.index');
            Route::get('/load-data', [OrderUnitController::class, 'get_data'])->name('order-unit.data');
        });
    });
});
