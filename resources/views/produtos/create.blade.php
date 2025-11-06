<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Cadastrar Novo Produto</h1>
        <hr>

        {{-- Formulário aponta para a rota 'produtos.store' que criaremos a seguir --}}
        <form action="{{ route('produtos.store') }}" method="POST">
            {{-- @csrf é uma diretiva de segurança OBRIGATÓRIA do Laravel contra ataques CSRF --}}
            @csrf

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
                {{-- O bloco @error mostra a mensagem de validação caso o campo 'nome' falhe --}}
                @error('nome') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao">{{ old('descricao') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="preco_venda" class="form-label">Preço de Venda (R$)</label>
                    <input type="number" step="0.01" class="form-control" id="preco_venda" name="preco_venda" value="{{ old('preco_venda') }}" required>
                    @error('preco_venda') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="quantidade_estoque" class="form-label">Quantidade em Estoque</label>
                    <input type="number" class="form-control" id="quantidade_estoque" name="quantidade_estoque" value="{{ old('quantidade_estoque') }}" required>
                    @error('quantidade_estoque') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                <select class="form-select" id="fornecedor_id" name="fornecedor_id" required>
                    <option value="">Selecione um fornecedor</option>
                    @foreach ($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id }}" {{ old('fornecedor_id') == $fornecedor->id ? 'selected' : '' }}>
                            {{ $fornecedor->nome }}
                        </option>
                    @endforeach
                </select>
                @error('fornecedor_id') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Salvar Produto</button>
            <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>