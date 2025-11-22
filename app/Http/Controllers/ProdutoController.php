<?php
// Em app/Http/Controllers/ProdutoController.php
namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Fornecedor;
use App\Http\Requests\StoreProdutoRequest; // IMPORTAR
use App\Http\Requests\UpdateProdutoRequest; // IMPORTAR
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Produto::class);

        // --- PREPARAÇÃO ---
        // Pegamos os filtros específicos que definimos
        $filters = $request->only(['filter_produto', 'filter_fornecedor', 'filter_categoria']);
        $sortBy = $request->query('sort_by', 'id');
        $sortDirection = $request->query('sort_direction', 'asc');
        $perPage = $request->query('per_page', 10);
        
        // --- CONSTRUÇÃO DA QUERY AVANÇADA ---
        $query = Produto::query()->withTrashed();

        // JOIN para Fornecedores: Permite buscar e ordenar pelo nome do fornecedor.
        $query->leftJoin('fornecedores', 'produtos.fornecedor_id', '=', 'fornecedores.id');

        $databaseDriver = DB::connection()->getDriverName();

        $aggregationQuery = null;
        if ($databaseDriver === 'pgsql') {
            // Sintaxe para PostgreSQL
            $aggregationQuery = DB::raw('STRING_AGG(DISTINCT categorias.nome, \', \') as nomes_categorias');
        } else {
            // Sintaxe padrão para MySQL/MariaDB
            $aggregationQuery = DB::raw('GROUP_CONCAT(DISTINCT categorias.nome ORDER BY categorias.nome SEPARATOR ", ") as nomes_categorias');
        }

        $query->select(
            'produtos.*',
            'fornecedores.razao_social as nome_fornecedor',
            $aggregationQuery // Injeta a Raw Query correta aqui
        )
        // JOIN para Categorias: Requer dois joins (pivot e tabela final).
        ->leftJoin('categoria_produto', 'produtos.id', '=', 'categoria_produto.produto_id')
        ->leftJoin('categorias', 'categoria_produto.categoria_id', '=', 'categorias.id');

        // --- APLICAÇÃO DOS FILTROS HÍBRIDOS ---
        // Filtro de Produto (Nome, SKU ou Barcode)
        $query->when($filters['filter_produto'] ?? null, function ($q, $search) {
            $q->where(function ($subquery) use ($search) {
                $subquery->where('produtos.nome', 'like', '%' . $search . '%')
                        ->orWhere('produtos.sku', 'like', '%' . $search . '%')
                        ->orWhere('produtos.barcode', 'like', '%' . $search . '%');
            });
        });

        // Filtro de Fornecedor (Razão Social ou CNPJ)
        $query->when($filters['filter_fornecedor'] ?? null, function ($q, $search) {
            $q->where(function ($subquery) use ($search) {
                $subquery->where('fornecedores.razao_social', 'like', '%' . $search . '%')
                        ->orWhere('fornecedores.cnpj', 'like', '%' . $search . '%');
            });
        });

        // Filtro de Categoria (Nome da Categoria ou Subcategoria)
        
        $query->when($filters['filter_categoria'] ?? null, function ($q, $search) use ($databaseDriver) {
            // Com PostgreSQL, a filtragem de categoria também precisa ser ajustada
            if ($databaseDriver === 'pgsql') {
                // No pgsql, não podemos usar o alias 'nomes_categorias' diretamente no HAVING assim.
                // A solução mais segura é filtrar dentro de uma subquery ou usar o `where` em uma query final.
                // Para simplicidade aqui, faremos a filtragem no próprio having, mas com a função original.
                $q->havingRaw('STRING_AGG(DISTINCT categorias.nome, \', \') LIKE ?', ['%' . $search . '%']);
            } else {
                // Usamos havingRaw porque estamos filtrando em uma coluna agregada (GROUP_CONCAT).
                $q->havingRaw('nomes_categorias LIKE ?', ['%' . $search . '%']);
            }
        });

        // Agrupamento: ESSENCIAL para evitar produtos duplicados por causa dos joins.
        $query->groupBy('produtos.id', 'fornecedores.razao_social');

        // --- ORDENAÇÃO AVANÇADA ---
        if ($sortBy === 'fornecedor') {
            $query->orderBy('nome_fornecedor', $sortDirection);
        } elseif ($sortBy === 'categoria') {
            $query->orderBy('nomes_categorias', $sortDirection);
        } else {
            // Ordena por colunas da tabela produtos (nome, preco_venda, id, etc.)
            $query->orderBy("produtos.{$sortBy}", $sortDirection);
        }
        
        // --- PAGINAÇÃO ---
        $produtos = $query->paginate($perPage);

        return view('produtos.index', compact('produtos', 'filters', 'sortBy', 'sortDirection', 'perPage'));
    }

    public function create()
    {
        $this->authorize('create', Produto::class);
        $fornecedores = Fornecedor::orderBy('razao_social')->get(); // Melhor ordenar
        return view('produtos.create', compact('fornecedores'));
    }

    public function store(StoreProdutoRequest $request) // USA O FORM REQUEST
    {
        Produto::create($request->validated());
        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit(Produto $produto)
    {
        $this->authorize('update', $produto);
        $fornecedores = Fornecedor::orderBy('razao_social')->get();
        return view('produtos.edit', compact('produto', 'fornecedores'));
    }

    public function update(UpdateProdutoRequest $request, Produto $produto) // USA O FORM REQUEST
    {
        $produto->update($request->validated());
        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $this->authorize('delete', $produto);
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Produto desativado com sucesso!');
    }

    public function restore(Produto $produto)
    {
        $this->authorize('restore', $produto);
        $produto->restore();
        return redirect()->route('produtos.index')->with('success', 'Produto restaurado com sucesso!');
    }
}