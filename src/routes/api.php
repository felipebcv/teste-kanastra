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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


use App\Http\Controllers\BoletoController;

Route::get('/boletos', [BoletoController::class, 'index']);
Route::get('/boletos/{id}', [BoletoController::class, 'show']);
Route::post('/boletos', [BoletoController::class, 'store']);
Route::put('/boletos/{boleto}', [BoletoController::class, 'update']);
Route::delete('/boletos/{boleto}', [BoletoController::class, 'destroy']);

// Rota para upload do CSV
Route::post('/boletos/upload', [BoletoController::class, 'uploadCsv']);