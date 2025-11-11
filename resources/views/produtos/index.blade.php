<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <!-- Incluindo o Bootstrap 5 via CDN para um estilo rápido -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Lista de Produtos</h1>
            <!-- Futuramente, este botão levará para a página de cadastro -->
            <a href="{{ route('produtos.create') }}" class="btn btn-primary">Adicionar Produto</a>
        </div>
        <hr>
        {{-- Bloco para exibir mensagens de sucesso --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Estoque</th>
                    <th scope="col">Fornecedor</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                {{-- A diretiva @forelse do Blade é perfeita para listas. --}}
                {{-- Ela faz um loop na variável $produtos (que veio do Controller)... --}}
                @forelse ($produtos as $produto)
                    <tr>
                        <th scope="row">{{ $produto->id }}</th>
                        <td>{{ $produto->nome }}</td>
                        <td>R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                        <td>{{ $produto->quantidade_estoque }}</td>
                        {{-- Acessamos o nome do fornecedor através do relacionamento que definimos no Model! --}}
                        <td>{{ $produto->fornecedor->nome ?? 'N/A' }}</td>
                        <td>
                            {{-- O método trashed() verifica se o produto foi "soft-deleted" --}}
                            @if ($produto->trashed())
                                <span class="badge bg-warning text-dark">Inativo</span>
                            @else
                                <span class="badge bg-success">Ativo</span>
                            @endif
                        </td>
                        <td class="d-flex">
                            @if ($produto->trashed())
                                {{-- Formulário para a ação de restaurar --}}
                                <form action="{{ route('produtos.restore', $produto->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-info">Restaurar</button>
                                </form>
                            @else
                                {{-- LÓGICA DE DELETAR (que já temos) --}}
                                <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-secondary me-2">Editar</a>
                                <form action="{{ route('produtos.destroy', $produto) }}" method="POST" onsubmit="return confirm('Deseja desativar este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-warning">Desativar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                {{-- ... e se a variável $produtos estiver vazia, ela mostra o bloco @empty. --}}
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Nenhum produto cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>