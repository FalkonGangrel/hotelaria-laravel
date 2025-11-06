<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController; // <-- IMPORTANTE: Importe o Controller

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

// Define uma rota do tipo GET para o endereço '/produtos'.
// Quando essa rota for acessada, ela executará o método 'index'
// da classe 'ProdutoController'.
Route::get('/produtos', [ProdutoController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
