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
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::resource('/proyectos', ProjectController::class)->middleware('auth');
Route::resource('/clientes', ClientController::class)->middleware('auth');
Route::resource('/cotizaciones', InvoiceController::class)->middleware('auth');
Route::resource('/proveedores', ProviderController::class)->middleware('auth');
Route::resource('/conceptos', InvoiceItemController::class)->middleware('auth');
Route::resource('/ingresos', IncomeController::class)->middleware('auth');
Route::resource('/egresos', OutcomeController::class)->middleware('auth');

Route::get('/download/invoice/{invoice}', [PDFController::class, 'publish'])->name('publish')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
