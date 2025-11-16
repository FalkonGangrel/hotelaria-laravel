@extends('layouts.app')

@section('title', 'Editar Usuário: ' . $user->name)

@section('content')
    <h1>Editar Usuário: <span class="text-primary">{{ $user->name }}</span></h1>
    <hr>

    {{-- Note a rota 'users.update' e o método PUT --}}
    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- ESSENCIAL: Informa ao Laravel que esta é uma requisição de atualização --}}

        <div class="row">
            {{-- Coluna 1: Campos principais --}}
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome Completo</label>
                    {{-- O segundo argumento de old() é o valor padrão, usado para preencher o formulário --}}
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Endereço de E-mail</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Este campo só aparece se o usuário logado tiver permissão para 'criar' (nosso proxy para admin/master) --}}
                @can('create', App\Models\User::class)
                    <div class="mb-3">
                        <label for="role" class="form-label">Função (Role)</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Usuário</option>
                            <option value="cliente" {{ old('role', $user->role) == 'cliente' ? 'selected' : '' }}>Cliente</option>
                            <option value="fornecedor" {{ old('role', $user->role) == 'fornecedor' ? 'selected' : '' }}>Fornecedor</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endcan

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Deixe em branco para não alterar">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
            </div>

            {{-- Coluna 2: Upload de foto com preview --}}
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="photo" class="form-label">Foto de Perfil (Opcional)</label>
                    <p>Foto Atual:</p>
                    <img src="{{ $user->photo_path ? asset('storage/' . $user->photo_path) : 'https://via.placeholder.com/100x140.png?text=Sem+Foto' }}"
                            alt="Foto de {{ $user->name }}" width="100" class="img-thumbnail mb-2">
                    <p class="form-text">Enviar uma nova foto irá substituir a atual.</p>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary">Atualizar Usuário</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection