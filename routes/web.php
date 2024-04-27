<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\OutcomeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth'])->name('dashboard');


Route::middleware(['auth','role:admin'])->group(function () {
    Route::resource('/proyectos', ProjectController::class);
    Route::resource('/clientes', ClientController::class);
    Route::resource('/cotizaciones', InvoiceController::class);
    Route::resource('/proveedores', ProviderController::class);
    Route::resource('/conceptos', InvoiceItemController::class);
    Route::resource('/ingresos', IncomeController::class);
    Route::resource('/egresos', OutcomeController::class);
    Route::resource('/archivos', FileController::class);
});


Route::get('/download/invoice/{invoice}', [PDFController::class, 'publish'])->name('publish')->middleware('auth');
// Route::get('/test/invoice/{invoice}', [PDFController::class, 'test'])->name('publish')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
