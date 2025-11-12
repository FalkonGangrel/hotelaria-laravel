<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Editar Fornecedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Fornecedor: {{ $fornecedor->nome }}</h1>
        <hr>
        <form action="{{ route('fornecedores.update', $fornecedor) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" value="{{ old('nome', $fornecedor->nome) }}" required>
                @error('nome') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ</label>
                <input type="text" class="form-control" name="cnpj" value="{{ old('cnpj', $fornecedor->cnpj) }}" required>
                @error('cnpj') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $fornecedor->email) }}">
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="{{ route('fornecedores.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>