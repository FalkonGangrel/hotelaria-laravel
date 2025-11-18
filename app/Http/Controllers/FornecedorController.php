<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        // ... sua lógica de ordenação e paginação (está perfeita) ...
        $sortBy = $request->query('sort_by', 'razao_social');
        $sortDirection = $request->query('sort_direction', 'asc');
        $perPage = $request->query('per_page', 10);
        $filters = $request->only(['filter_q']); // MUDANÇA: Usaremos um filtro genérico 'q' (de query)

        $query = Fornecedor::withTrashed();

        // MUDANÇA: Lógica de busca aprimorada
        // Agora, quando o usuário digitar no campo de busca, ele procurará
        // tanto na razão social quanto no nome fantasia.
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

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        // MUDANÇA: Validação sincronizada com a nova migration
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
            // user_id será tratado separadamente
        ]);

        Fornecedor::create($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function show(Fornecedor $fornecedor)
    {
        // Geralmente não é necessário em painéis de admin
    }

    public function edit(Fornecedor $fornecedor)
    {
        return view('fornecedores.edit', ['fornecedor' => $fornecedor]);
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        // MUDANÇA: Validação sincronizada para o update
        $validatedData = $request->validate([
            'razao_social' => ['required', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', Rule::unique('fornecedores', 'cnpj')->ignore($fornecedor->id)],
            'ie' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('fornecedores', 'email')->ignore($fornecedor->id)],
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

        $fornecedor->update($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor desativado com sucesso!');
    }

    public function restore(Fornecedor $fornecedor) // MUDANÇA: Recebendo o Model diretamente
    {
        $fornecedor->restore();
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor restaurado com sucesso!');
    }
}