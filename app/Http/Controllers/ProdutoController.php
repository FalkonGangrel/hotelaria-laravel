<?php

namespace App\Http\Controllers;

use App\Models\Produto; // <-- IMPORTANTE: Importe o Model Produto
use App\Models\Fornecedor; // Importar o Model Fornecedor
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // 1. Buscamos os produtos E seus fornecedores de forma otimizada
        $produtos = Produto::withTrashed()->with('fornecedor')->get();

        // 2. Retornamos a VIEW, passando a variável 'produtos' para ela
        return view('produtos.index', ['produtos' => $produtos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Buscamos todos os fornecedores para popular um campo <select> no formulário.
        // Assim, o usuário pode escolher um fornecedor existente.
        $fornecedores = Fornecedor::all();

        // Retornamos a view do formulário de criação
        return view('produtos.create', ['fornecedores' => $fornecedores]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDAÇÃO
        // O Laravel torna a validação incrivelmente simples.
        // Se a validação falhar, o Laravel automaticamente redireciona
        // o usuário de volta para o formulário, com as mensagens de erro.
        $validatedData = $request->validate([
            'descricao' => 'nullable|string',
            'fornecedor_id' => 'required|exists:fornecedores,id', // Garante que o ID do fornecedor existe na tabela 'fornecedores'
        ]);

        // 2. CRIAÇÃO
        // Se a validação passar, criamos o produto no banco de dados.
        // O método 'create' usa o "Mass Assignment", que veremos no próximo passo.
        Produto::create($validatedData);

        // 3. REDIRECIONAMENTO
        // Após salvar, redirecionamos o usuário para a página de listagem (index)
        // com uma mensagem de sucesso (flash message).
        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        //
        $produto->delete();

        return redirect()->route('produtos.index')->with('success', 'Produto desativado com sucesso!');
    }
}
