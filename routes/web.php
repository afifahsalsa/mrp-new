<?php

use App\Http\Controllers\BufferController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OpenPoController;
use App\Http\Controllers\StokController;
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
    Route::prefix('/buffer')->group(function () {
        Route::get('/', [BufferController::class, 'index'])->name('buffer.index');
        Route::get('format-import', [BufferController::class, 'format_buffer'])->name('buffer.format-import');
        Route::get('load-data', [BufferController::class, 'getData'])->name('buffer.data');
        Route::post('import', [BufferController::class, 'import'])->name('buffer.import');
        Route::delete('delete', [BufferController::class, 'destroy'])->name('buffer.delete');
    });
    Route::prefix('/stok')->group(function(){
        Route::get('/', [StokController::class, 'index'])->name('stok.index');
        Route::get('format-import', [StokController::class, 'format_stok'])->name('stok.format');
        Route::get('load-data', [StokController::class, 'getData'])->name('stok.data');
        Route::post('import', [StokController::class, 'import'])->name('stok.import');
        Route::delete('delete', [StokController::class, 'destroy'])->name('stok.delete');
    });
    Route::prefix('/open-po')->group(function(){
        Route::get('/', [OpenPoController::class, 'index'])->name('open-po.index');
        Route::get('format-openpo', [OpenPoController::class, 'get_format'])->name('open-po.format');
        Route::get('load-data', [OpenPoController::class, 'get_data'])->name('open-po.data');
        Route::post('import', [OpenPoController::class, 'import'])->name('open-po.import');
        Route::get('export', [OpenPoController::class, 'export'])->name('open-po.export');
        Route::delete('delete', [OpenPoController::class, 'destroy'])->name('open-po.delete');
    });
});
