@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    {{-- Bloco do Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Produtos</h1>
        @can('create', App\Models\Produto::class)
            <a href="{{ route('produtos.create') }}" class="btn btn-primary">Novo Produto</a>
        @endcan
    </div>
    <hr>

    {{-- Legenda de Status --}}
    <div class="d-flex align-items-center flex-wrap gap-3 mb-3">
        <strong>Legenda:</strong>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-success me-1">&nbsp;</span> Ativo
        </div>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-primary me-1">&nbsp;</span> Em Pedido
        </div>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-warning me-1">&nbsp;</span> Sem Estoque
        </div>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-danger me-1">&nbsp;</span> Inativo / Desativado
        </div>
    </div>

    {{-- Formulário de Filtros Avançados --}}
    <form action="{{ route('produtos.index') }}" method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="filter_produto" class="form-control" placeholder="Buscar por Nome, SKU ou Código..." value="{{ $filters['filter_produto'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="filter_fornecedor" class="form-control" placeholder="Buscar por Fornecedor ou CNPJ..." value="{{ $filters['filter_fornecedor'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="filter_categoria" class="form-control" placeholder="Buscar por Categoria..." value="{{ $filters['filter_categoria'] ?? '' }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary flex-grow-1">Buscar</button>
                <a href="{{ route('produtos.index') }}" class="btn btn-outline-danger" title="Limpar Filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>

    {{-- Tabela Responsiva --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    @php 
                        $linkParamsNome = array_merge($filters, ['sort_direction' => $sortBy == 'nome' && $sortDirection == 'asc' ? 'desc' : 'asc', 'sort_by' => 'nome']); 
                        $linkParamsFornecedor = array_merge($filters, ['sort_direction' => $sortBy == 'fornecedor' && $sortDirection == 'asc' ? 'desc' : 'asc', 'sort_by' => 'fornecedor']);
                        $linkParamsCategoria = array_merge($filters, ['sort_direction' => $sortBy == 'categoria' && $sortDirection == 'asc' ? 'desc' : 'asc', 'sort_by' => 'categoria']);
                        $linkParamsPreco = array_merge($filters, ['sort_direction' => $sortBy == 'preco_venda' && $sortDirection == 'asc' ? 'desc' : 'asc', 'sort_by' => 'preco_venda']);
                    @endphp
                    <th scope="col">
                        <a href="{{ route('produtos.index', $linkParamsNome) }}">
                            Produto
                            @if($sortBy == 'nome') <i class="bi bi-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i> @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('produtos.index', $linkParamsFornecedor) }}">
                            Fornecedor
                            @if($sortBy == 'fornecedor') <i class="bi bi-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i> @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('produtos.index', $linkParamsCategoria) }}">
                            Categorias
                            @if($sortBy == 'categoria') <i class="bi bi-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i> @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('produtos.index', $linkParamsPreco) }}">
                            Preço Venda
                            @if($sortBy == 'preco_venda') <i class="bi bi-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i> @endif
                        </a>
                    </th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produtos as $produto)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                {{-- Indicador de Status --}}
                                @if($produto->trashed())
                                    <span class="badge rounded-pill bg-danger me-2" title="Desativado">&nbsp;</span>
                                @elseif($produto->status === 'ativo')
                                    <span class="badge rounded-pill bg-success me-2" title="Ativo">&nbsp;</span>
                                @elseif($produto->status === 'em_pedido')
                                    <span class="badge rounded-pill bg-primary me-2" title="Em Pedido">&nbsp;</span>
                                @elseif($produto->status === 'sem_estoque')
                                    <span class="badge rounded-pill bg-warning me-2" title="Sem Estoque">&nbsp;</span>
                                @else
                                    <span class="badge rounded-pill bg-danger me-2" title="Inativo">&nbsp;</span>
                                @endif

                                {{-- Informações do Produto --}}
                                <div>
                                    <strong>{{ $produto->nome }}</strong>
                                    @if($produto->sku)<br><small class="text-muted">SKU: {{ $produto->sku }}</small>@endif
                                    @if($produto->barcode)<br><small class="text-muted"><i class="bi bi-upc-scan"></i> {{ $produto->barcode }}</small>@endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $produto->nome_fornecedor ?? 'N/A' }}</td>
                        <td><small>{{ $produto->nomes_categorias ?? 'Sem categoria' }}</small></td>
                        <td>R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($produto->trashed())
                                @can('restore', $produto)
                                <form action="{{ route('produtos.restore', $produto) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="Restaurar"><i class="bi bi-arrow-counterclockwise"></i></button>
                                </form>
                                @endcan
                            @else
                                @can('update', $produto)
                                <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                @endcan
                                @can('delete', $produto)
                                <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja desativar este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Desativar"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum produto encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center">
        {{ $produtos->appends(request()->query())->links() }}
    </div>
@endsection