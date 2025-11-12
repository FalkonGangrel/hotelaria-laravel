<?php

// Linhas para importar controllers
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FornecedorController;

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

Route::get('/', function () {
    return view('welcome');
});


// Rota para o recurso 'produtos', mapeando para o ProdutoController
Route::resource('produtos', ProdutoController::class);
Route::patch('produtos/{produto}/restore', [ProdutoController::class, 'restore'])->name('produtos.restore');

// Rota para o recurso 'fornecedores', mapeando para o FornecedorController
Route::resource('fornecedores', FornecedorController::class)->parameters(['fornecedores' => 'fornecedor']);
Route::patch('fornecedores/{fornecedor}/restore', [FornecedorController::class, 'restore'])->name('fornecedores.restore');