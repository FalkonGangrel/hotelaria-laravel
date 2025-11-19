<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Http\Requests\UpdateFornecedorRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Importar o Facade de Autenticação

/**
 * Controlador para o CRUD de Fornecedores.
 * 
 * Este controller depende de:
 * 1. FornecedorPolicy: Para autorizar as ações de cada usuário.
 * 2. FornecedorScope (Global): Para filtrar automaticamente os resultados que um usuário pode ver.
 * 3. Model Events (no model Fornecedor): Para alterar o status automaticamente no soft delete/restore.
 */
class FornecedorController extends Controller
{
    /**
     * Exibe a lista de fornecedores.
     * A filtragem para usuários do tipo 'fornecedor' é feita automaticamente pelo Global Scope.
     */
    public function index(Request $request)
    {
        // Autoriza a visualização da página de listagem.
        // Requer o método 'viewAny' na FornecedorPolicy.
        $this->authorize('viewAny', Fornecedor::class);

        $sortBy = $request->query('sort_by', 'razao_social');
        $sortDirection = $request->query('sort_direction', 'asc');
        $perPage = $request->query('per_page', 10);
        $filters = $request->only(['filter_q']);

        $query = Fornecedor::withTrashed();

        $query->when($filters['filter_q'] ?? null, function ($q, $search) {
            $q->where(function ($subquery) use ($search) {
                $subquery->where('razao_social', 'like', '%' . $search . '%')
                        ->orWhere('nome_fantasia', 'like', '%' . $search . '%')
                        ->orWhere('cnpj', 'like', '%' . $search . '%');
            });
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

    /**
     * Mostra o formulário para criar um novo fornecedor.
     */
    public function create()
    {
        // Autoriza se o usuário pode criar um novo fornecedor.
        // Requer o método 'create' na FornecedorPolicy.
        $this->authorize('create', Fornecedor::class);

        return view('fornecedores.create');
    }

    /**
     * Salva um novo fornecedor no banco de dados.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Fornecedor::class);

        $validatedData = $request->validate([
            'razao_social' => ['required', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', Rule::unique('fornecedores', 'cnpj')],
            'ie' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('fornecedores', 'email')],
            'email2' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'telefone2' => ['nullable', 'string', 'max:20'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:50'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:100'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'uf' => ['nullable', 'string', 'max:2'],
            'cep' => ['nullable', 'string', 'max:9'],
            'status' => ['required', 'string', Rule::in(['ativo', 'inativo', 'em_analise', 'suspenso'])],
            'observacoes' => ['nullable', 'string'],
        ]);

        // Associa o ID do usuário logado ao novo fornecedor.
        $validatedData['user_id'] = Auth::id();

        Fornecedor::create($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    /**
     * Exibe um fornecedor específico (opcional).
     */
    public function show(Fornecedor $fornecedor)
    {
        // Autoriza se o usuário pode ver este fornecedor específico.
        // Requer o método 'view' na FornecedorPolicy.
        $this->authorize('view', $fornecedor);

        // Implemente uma view se precisar de uma página de detalhes.
        // Ex: return view('fornecedores.show', compact('fornecedor'));
    }

    /**
     * Mostra o formulário para editar um fornecedor.
     */
    public function edit(Fornecedor $fornecedor)
    {
        // Autoriza se o usuário pode atualizar este fornecedor.
        // Requer o método 'update' na FornecedorPolicy.
        $this->authorize('update', $fornecedor);

        return view('fornecedores.edit', ['fornecedor' => $fornecedor]);
    }

    /**
     * Atualiza um fornecedor no banco de dados.
     */
    public function update(UpdateFornecedorRequest $request, Fornecedor $fornecedor)
    {
        $this->authorize('update', $fornecedor);

        $fornecedor->update($request->validated());

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    /**
     * "Desativa" um fornecedor (Soft Delete).
     * A lógica para mudar o status para 'inativo' está no Model Event.
     */
    public function destroy(Fornecedor $fornecedor)
    {
        // Autoriza se o usuário pode deletar este fornecedor.
        // Requer o método 'delete' na FornecedorPolicy.
        $this->authorize('delete', $fornecedor);

        $fornecedor->delete();

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor desativado com sucesso!');
    }

    /**
     * Restaura um fornecedor "desativado".
     * A lógica para mudar o status para 'em_analise' está no Model Event.
     */
    public function restore(Fornecedor $fornecedor)
    {
        // Autoriza se o usuário pode restaurar este fornecedor.
        // Requer o método 'restore' na FornecedorPolicy.
        $this->authorize('restore', $fornecedor);

        $fornecedor->restore();

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor restaurado com sucesso!');
    }
}