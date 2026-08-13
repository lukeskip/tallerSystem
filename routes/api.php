<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\ProjectController as ApiProjectController;
use App\Http\Controllers\Api\InvoiceController as ApiInvoiceController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('v1')->group(function () {
        Route::apiResource('proyectos', ApiProjectController::class)->only(['index', 'show']);
        Route::apiResource('cotizaciones', ApiInvoiceController::class)->only(['index', 'show']);
    });
});

