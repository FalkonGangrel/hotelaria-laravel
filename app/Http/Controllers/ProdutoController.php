<?php
namespace App\Http\Controllers;

use App\Models\Produto; // <-- IMPORTANTE: Importe o Model Produto
use App\Models\Fornecedor; // Importar o Model Fornecedor
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'nome' => ['required', 'string'],
            'descricao' => ['nullable','string'],
            'preco_venda' => ['required', 'numeric', 'min:0'],
            'quantidade_estoque' => ['required', 'integer', 'min:0'],
            'estoque_minimo' => ['nullable', 'integer', 'min:0'],
            // Validações específicas para o fornecedor
            'fornecedor_id' => [
                'required',
                Rule::exists('fornecedores', 'id'), // Garante que o ID do fornecedor existe na tabela 'fornecedores'
            ],
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
    public function edit(Produto $produto)
    {
        $fornecedores = Fornecedor::all();

        // Retornamos a view do formulário de criação
        return view('produtos.edit', ['produto' => $produto, 'fornecedores' => $fornecedores]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produto $produto)
    {
        $validatedData = $request->validate([
            'nome' => ['required', 'string'],
            'descricao' => ['nullable','string'],
            'preco_venda' => ['required', 'numeric', 'min:0'],
            'quantidade_estoque' => ['required', 'integer', 'min:0'],
            'estoque_minimo' => ['nullable', 'integer', 'min:0'],
            // Validações específicas para o fornecedor
            'fornecedor_id' => [
                'required',
                Rule::exists('fornecedores', 'id'), // Garante que o ID do fornecedor existe na tabela 'fornecedores'
            ],
        ]);
        $produto->update($validatedData);
        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
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

    /**
     * Restaura um produto que foi "soft-deleted".
     *
     * @param  int  $id O ID do produto a ser restaurado.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        // 1. ENCONTRAR O PRODUTO "DELETADO"
        // É CRUCIAL usar o método withTrashed() aqui. Sem ele, o Laravel
        // não encontrará o produto, pois ele está "no lixo".
        // findOrFail() tentará encontrar e, se não conseguir, gerará um erro 404.
        $produto = Produto::withTrashed()->findOrFail($id);

        // 2. RESTAURAR
        // A trait SoftDeletes nos dá este método mágico. Ele simplesmente
        // define a coluna 'deleted_at' de volta para NULL.
        $produto->restore();

        // 3. REDIRECIONAR
        return redirect()->route('produtos.index')->with('success', 'Produto restaurado com sucesso!');
    }
}
