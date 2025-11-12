<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Fornecedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Lista de Fornecedores</h1>
        <a href="{{ route('produtos.index') }}" class="btn btn-info mb-2">Ver Produtos</a>
        <hr>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('fornecedores.create') }}" class="btn btn-primary mb-3">Adicionar Fornecedor</a>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fornecedores as $fornecedor)
                    <tr>
                        <td>{{ $fornecedor->id }}</td>
                        <td>{{ $fornecedor->nome }}</td>
                        <td>{{ $fornecedor->cnpj }}</td>
                        <td>{{ $fornecedor->email ?? 'N/A' }}</td>
                        <td>
                            @if ($fornecedor->trashed())
                                <span class="badge bg-warning text-dark">Inativo</span>
                            @else
                                <span class="badge bg-success">Ativo</span>
                            @endif
                        </td>
                        <td class="d-flex">
                            @if ($fornecedor->trashed())
                                {{-- Formulário para a ação de restaurar --}}
                                <form action="{{ route('fornecedores.restore', $fornecedor->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-info">Restaurar</button>
                                </form>
                            @else
                                <a href="{{ route('fornecedores.edit', $fornecedor) }}" class="btn btn-sm btn-secondary me-2">Editar</a>
                                <form action="{{ route('fornecedores.destroy', $fornecedor) }}" method="POST" onsubmit="return confirm('Deseja desativar este fornecedor?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-warning">Desativar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum fornecedor cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>