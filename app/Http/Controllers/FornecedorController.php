<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {

        $sortBy = $request->query('sort_by', 'id'); // Padrão é ordenar por ID
        $sortDirection = $request->query('sort_direction', 'asc'); // Padrão é ascendente
        $perPage = $request->query('per_page', 10); // Padrão é 10 itens por página
        $filters = $request->only(['filter_nome']); // Pega apenas os filtros relevantes

        $query = Fornecedor::withTrashed();
        $query->when($filters['filter_nome'] ?? null, function ($q, $nome) {
            $q->where('nome', 'like', '%' . $nome . '%');
        });
        $query->orderBy($sortBy, $sortDirection);
        $fornecedores = $query->paginate($perPage);

        return view('fornecedores.index', [
            'fornecedores' => $fornecedores,
            'filters' => $filters,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        // 1. A validação é executada E o resultado é salvo na variável $validatedData.
        $validatedData = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => [
                'required',
                'string',
                'max:18',
                Rule::unique('fornecedores', 'cnpj'),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('fornecedores', 'email'),
            ],
        ]);

        // 2. A variável $validatedData (que contém os dados limpos e seguros)
        // é passada para o método create().
        Fornecedor::create($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function show(Fornecedor $fornecedor)
    {
        // Lógica para visualizar um único fornecedor, se necessário.
    }

    public function edit(Fornecedor $fornecedor)
    {
        return view('fornecedores.edit', ['fornecedor' => $fornecedor]);
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $validatedData = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => [
                'required',
                'string',
                'max:18',
                Rule::unique('fornecedores', 'cnpj')->ignore($fornecedor->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('fornecedores', 'email')->ignore($fornecedor->id),
            ],
        ]);

        $fornecedor->update($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor desativado com sucesso!');
    }

    public function restore($id)
    {
        $fornecedor = Fornecedor::withTrashed()->findOrFail($id);

        // 2. RESTAURAR
        // A trait SoftDeletes nos dá este método mágico. Ele simplesmente
        // define a coluna 'deleted_at' de volta para NULL.
        $fornecedor->restore();

        // 3. REDIRECIONAR
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor restaurado com sucesso!');
    }

}