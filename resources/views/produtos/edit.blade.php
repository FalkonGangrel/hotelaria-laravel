{{-- 1. Diz ao Blade que esta view estende o nosso layout mestre --}}
@extends('layouts.app')

{{-- 2. Define o conteúdo da seção 'title' que está no layout mestre --}}
@section('title', 'Edição de Produtos')

{{-- 3. Todo o conteúdo específico da página vai dentro da seção 'content' --}}
@section('content')
    <h1>Editar Produto: {{ $produto->nome }}</h1>
    <hr>
    <form action="{{ route('produtos.update', $produto) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
            {{-- O bloco @error mostra a mensagem de validação caso o campo 'nome' falhe --}}
            @error('nome') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao">{{ old('descricao', $produto->descricao) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="preco_venda" class="form-label">Preço de Venda (R$)</label>
                <input type="number" step="0.01" class="form-control" id="preco_venda" name="preco_venda" value="{{ old('preco_venda', $produto->preco_venda) }}" required>
                @error('preco_venda') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="quantidade_estoque" class="form-label">Quantidade em Estoque</label>
                <input type="number" class="form-control" id="quantidade_estoque" name="quantidade_estoque" value="{{ old('quantidade_estoque', $produto->quantidade_estoque) }}" required>
                @error('quantidade_estoque') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="fornecedor_id" class="form-label">Fornecedor</label>
            <select class="form-select" id="fornecedor_id" name="fornecedor_id" required>
                <option value="">Selecione um fornecedor</option>
                @foreach ($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->id }}" {{ (old('fornecedor_id') == $produto->fornecedor_id || $produto->fornecedor_id == $fornecedor->id ) ? 'selected' : '' }}>
                        {{ $fornecedor->nome }}
                    </option>
                @endforeach
            </select>
            @error('fornecedor_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection