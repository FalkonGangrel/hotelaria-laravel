<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Pequeno ajuste para o ícone de ordenação */
        th a { text-decoration: none; color: inherit; }
        th a:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Lista de Produtos</h1>
        <a href="{{ route('fornecedores.index') }}" class="btn btn-info mb-2">Ver Fornecedores</a>
        <hr>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- SEÇÃO DE FILTROS E CONTROLES -->
        <div class="card bg-light mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('produtos.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label for="filter_nome" class="form-label">Filtrar por Nome:</label>
                        <input type="text" name="filter_nome" id="filter_nome" class="form-control" value="{{ $filters['filter_nome'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label for="filter_fornecedor" class="form-label">Filtrar por Fornecedor:</label>
                        <input type="text" name="filter_fornecedor" id="filter_fornecedor" class="form-control" value="{{ $filters['filter_fornecedor'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label for="per_page" class="form-label">Itens por página:</label>
                        <select name="per_page" id="per_page" class="form-select">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Limpar</a>
                    </div>
                </form>
            </div>
        </div>

        <a href="{{ route('produtos.create') }}" class="btn btn-primary mb-3">Adicionar Produto</a>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    @php
                        // Lógica para inverter a direção da ordenação
                        $linkParams = array_merge($filters, ['per_page' => $perPage]);
                        $direction = ($sortBy == 'id' && $sortDirection == 'asc') ? 'desc' : 'asc';
                    @endphp
                    <th><a href="{{ route('produtos.index', array_merge($linkParams, ['sort_by' => 'id', 'sort_direction' => $direction])) }}">ID {!! $sortBy == 'id' ? ($sortDirection == 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                    
                    @php $direction = ($sortBy == 'nome' && $sortDirection == 'asc') ? 'desc' : 'asc'; @endphp
                    <th><a href="{{ route('produtos.index', array_merge($linkParams, ['sort_by' => 'nome', 'sort_direction' => $direction])) }}">Nome {!! $sortBy == 'nome' ? ($sortDirection == 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>

                    <th>Preço Venda</th>
                    <th>Estoque</th>
                    <th>Fornecedor</th>
                    <th>Status</th>
                    <th style="width: 180px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produtos as $produto)
                    <tr>
                        <td>{{ $produto->id }}</td>
                        <td>{{ $produto->nome }}</td>
                        <td>R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                        <td>{{ $produto->quantidade_estoque }}</td>
                        <td>{{ $produto->fornecedor->nome ?? 'N/A' }}</td>
                        <td>
                            @if ($produto->trashed())
                                <span class="badge bg-warning text-dark">Inativo</span>
                            @else
                                <span class="badge bg-success">Ativo</span>
                            @endif
                        </td>
                        <td class="d-flex">
                            @if ($produto->trashed())
                                <form action="{{ route('produtos.restore', $produto->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-info">Restaurar</button>
                                </form>
                            @else
                                <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-secondary me-2">Editar</a>
                                <form action="{{ route('produtos.destroy', $produto) }}" method="POST" onsubmit="return confirm('Deseja desativar este produto?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-warning">Desativar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Nenhum produto encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- RODAPÉ: LINKS DE PAGINAÇÃO -->
        <div class="d-flex justify-content-center">
            {{-- O método appends() é CRUCIAL. Ele adiciona todos os parâmetros atuais (filtros, ordenação)
                aos links de paginação, para que você não perca seu filtro ao mudar de página. --}}
            {!! $produtos->appends(request()->query())->links() !!}
        </div>
    </div>
</body>
</html>