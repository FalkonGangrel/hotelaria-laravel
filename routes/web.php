<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FornecedorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
| Qualquer visitante pode acessar estas rotas.
*/

Route::get('/', function () {
    return view('welcome'); // A página inicial pública
});


/*
|--------------------------------------------------------------------------
| Rotas Protegidas (CMS / Área Privada)
|--------------------------------------------------------------------------
| APENAS usuários autenticados podem acessar o que está aqui dentro.
| O middleware 'auth' funciona como um porteiro.
*/

Route::middleware('auth')->group(function () {

    // Rota do Dashboard (página inicial após o login)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas do Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // NOSSAS ROTAS DE PRODUTOS
    Route::resource('produtos', ProdutoController::class);
    Route::patch('produtos/{produto}/restore', [ProdutoController::class, 'restore'])->name('produtos.restore');

    // NOSSAS ROTAS DE FORNECEDORES
    Route::resource('fornecedores', FornecedorController::class)->parameters([
        'fornecedores' => 'fornecedor'
    ]);
    Route::patch('fornecedores/{fornecedor}/restore', [FornecedorController::class, 'restore'])->name('fornecedores.restore');
});


/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
| O Breeze gerencia as rotas de login, registro, logout, etc.
*/
require __DIR__.'/auth.php';