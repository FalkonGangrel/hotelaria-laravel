@extends('layouts.app')

@section('title', 'Adicionar Novo Usuário')

@section('content')
    <h1>Adicionar Novo Usuário</h1>
    <hr>

    {{-- O atributo enctype="multipart/form-data" é OBRIGATÓRIO para formulários com upload de arquivos --}}
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- Coluna 1: Campos principais --}}
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Endereço de E-mail</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Função (Role)</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="">Selecione uma função</option>
                        {{-- Por segurança, a role 'master' não pode ser atribuída pela interface --}}
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuário</option>
                        <option value="cliente" {{ old('role') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                        <option value="fornecedor" {{ old('role') == 'fornecedor' ? 'selected' : '' }}>Fornecedor</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
            </div>

            {{-- Coluna 2: Upload de foto --}}
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="photo" class="form-label">Foto de Perfil (Opcional)</label>
                    <p class="form-text">A imagem será redimensionada para 50x70 pixels. Máx 7MB.</p>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary">Salvar Usuário</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection