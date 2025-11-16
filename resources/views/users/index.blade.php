@extends('layouts.app')

@section('title', 'Gerenciamento de Usuários')

@section('content')
    <h1>Gerenciamento de Usuários</h1>
    <hr>

    {{-- Mostra o botão "Novo Usuário" apenas se o usuário logado tiver permissão para 'criar' usuários.
        A policy 'create' retorna true para master e admin. --}}
    @can('create', App\Models\User::class)
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Adicionar Novo Usuário</a>
    @endcan

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th style="width: 70px;">Foto</th>
                @php
                    $linkParams = array_merge($filters, ['per_page' => $perPage]);
                    $direction = ($sortBy == 'name' && $sortDirection == 'asc') ? 'desc' : 'asc';
                @endphp
                <th><a href="{{ route('users.index', array_merge($linkParams, ['sort_by' => 'name', 'sort_direction' => $direction])) }}">Nome {!! $sortBy == 'name' ? ($sortDirection == 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                <th>Função (Role)</th>
                <th>Status</th>
                <th style="width: 220px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>
                        {{-- Exibe a foto do usuário ou uma imagem padrão (placeholder) --}}
                        <img src="{{ $user->photo_path ? asset('storage/' . $user->photo_path) : 'https://via.placeholder.com/50x70.png?text=Foto' }}"
                            alt="Foto de {{ $user->name }}" width="50" height="70" class="rounded">
                    </td>
                    <td>{{ $user->name }}</td>
                    <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                    <td>
                        @if ($user->trashed())
                            <span class="badge bg-warning text-dark">Inativo</span>
                        @else
                            <span class="badge bg-success">Ativo</span>
                        @endif
                    </td>
                    <td>
                        {{-- Botão EDITAR: Só aparece se o usuário logado puder 'atualizar' este usuário específico da linha. --}}
                        @can('update', $user)
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-secondary">Editar</a>
                        @endcan

                        @if ($user->trashed())
                            {{-- Botão REATIVAR: Só aparece para usuários inativos e se houver permissão de 'restaurar'. --}}
                            @can('restore', $user)
                                <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja reativar este usuário?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-info">Reativar</button>
                                </form>
                            @endcan
                        @else
                            {{-- Botão DESATIVAR: Só aparece para usuários ativos e se houver permissão de 'deletar'. --}}
                            @can('delete', $user)
                                {{-- Adicionamos uma segurança extra para que um usuário não possa desativar a si mesmo. --}}
                                @if(Auth::user()->id !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja desativar este usuário?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-warning">Desativar</button>
                                    </form>
                                @endif
                            @endcan
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection