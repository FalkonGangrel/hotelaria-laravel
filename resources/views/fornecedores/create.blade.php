{{-- 1. Diz ao Blade que esta view estende o nosso layout mestre --}}
@extends('layouts.app')

{{-- 2. Define o conteúdo da seção 'title' que está no layout mestre --}}
@section('title', 'Cadastro de Fornecedores')

{{-- 3. Todo o conteúdo específico da página vai dentro da seção 'content' --}}
@section('content')
    <h1>Cadastrar Novo Fornecedor</h1>
    <hr>
    <form action="{{ route('fornecedores.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" required>
            @error('nome') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="cnpj" class="form-label">CNPJ</label>
            <input type="text" class="form-control" name="cnpj" value="{{ old('cnpj') }}" required>
            @error('cnpj') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('fornecedores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection