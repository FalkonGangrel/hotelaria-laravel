@extends('layouts.app')

@section('title', 'Gerenciamento de Usuários')

@section('content')
    {{-- Bloco do Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Gerenciamento de Usuários</h1>
        @can('create', App\Models\User::class)
            <a href="{{ route('users.create') }}" class="btn btn-primary">Novo Usuário</a>
        @endcan
    </div>
    <hr>

    {{-- Legenda de Status --}}
    <div class="d-flex align-items-center gap-3 mb-3">
        <strong>Legenda:</strong>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-success me-1">&nbsp;</span> Ativo
        </div>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-danger me-1">&nbsp;</span> Inativo
        </div>
    </div>

    {{-- Formulário de Busca Unificado --}}
    <form action="{{ route('users.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            {{-- Para que este filtro funcione, precisaremos ajustar o UserController --}}
            <input type="text" name="filter_q" class="form-control" placeholder="Buscar por Nome ou E-mail..." value="{{ $filters['filter_q'] ?? '' }}">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-danger" title="Limpar Filtros"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>

    {{-- Tabela Responsiva --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    {{-- Parâmetros para os links de ordenação --}}
                    @php
                        $linkParamsNome = array_merge($filters ?? [], ['per_page' => $perPage, 'sort_by' => 'name', 'sort_direction' => $sortBy == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc']);
                        $linkParamsRole = array_merge($filters ?? [], ['per_page' => $perPage, 'sort_by' => 'role', 'sort_direction' => $sortBy == 'role' && $sortDirection == 'asc' ? 'desc' : 'asc']);
                    @endphp
                    <th scope="col" style="width: 70px;">Foto</th>
                    <th scope="col">
                        <a href="{{ route('users.index', $linkParamsNome) }}">
                            Nome / E-mail
                            @if($sortBy == 'name') <i class="bi bi-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i> @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('users.index', $linkParamsRole) }}">
                            Função
                            @if($sortBy == 'role') <i class="bi bi-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i> @endif
                        </a>
                    </th>
                    <th scope="col" class="text-center" style="width: 220px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <img src="{{ $user->photo_path ? asset('storage/' . $user->photo_path) : 'https://via.placeholder.com/50x70.png?text=Foto' }}"
                                alt="Foto de {{ $user->name }}" width="50" height="70" class="rounded">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                {{-- Indicador de Status --}}
                                @if ($user->trashed())
                                    <span class="badge rounded-pill bg-danger me-2" title="Inativo">&nbsp;</span>
                                @else
                                    <span class="badge rounded-pill bg-success me-2" title="Ativo">&nbsp;</span>
                                @endif
                                <div>
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></td>
                        <td class="text-center">
                            @if ($user->trashed())
                                @can('restore', $user)
                                <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja reativar este usuário?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="Reativar"><i class="bi bi-arrow-counterclockwise"></i></button>
                                </form>
                                @endcan
                            @else
                                @can('update', $user)
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                @endcan

                                @can('delete', $user)
                                    @if(Auth::user()->id !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja desativar este usuário?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Desativar"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endif
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Nenhum usuário encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->query())->links() }}
    </div>
@endsection