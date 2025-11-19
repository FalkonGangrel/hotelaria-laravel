@extends('layouts.app')

@section('title', 'Fornecedores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Fornecedores</h1>
        @can('create', App\Models\Fornecedor::class)
            <a href="{{ route('fornecedores.create') }}" class="btn btn-primary">Cadastrar Novo</a>
        @endcan
    </div>
    <hr>

    <div class="d-flex align-items-center gap-3 mb-3">
        <strong>Legenda:</strong>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-success me-1">&nbsp;</span> Ativo
        </div>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-warning me-1">&nbsp;</span> Em Análise
        </div>
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill bg-danger me-1">&nbsp;</span> Inativo / Suspenso
        </div>
    </div>

    <form action="{{ route('fornecedores.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="filter_q" class="form-control" placeholder="Buscar por Razão Social, Nome Fantasia ou CNPJ..." value="{{ $filters['filter_q'] ?? '' }}">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            <a href="{{ route('fornecedores.index') }}" class="btn btn-outline-danger">Limpar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    @php $linkParamsRazaoSocial = array_merge($filters, ['sort_direction' => $sortBy == 'razao_social' && $sortDirection == 'asc' ? 'desc' : 'asc']); @endphp
                    <th scope="col">
                        <a href="{{ route('fornecedores.index', array_merge($linkParamsRazaoSocial, ['sort_by' => 'razao_social'])) }}">
                            Razão Social / CNPJ
                            @if($sortBy == 'razao_social') <i class="bi bi-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i> @endif
                        </a>
                    </th>
                    
                    @php $linkParamsNomeFantasia = array_merge($filters, ['sort_direction' => $sortBy == 'nome_fantasia' && $sortDirection == 'asc' ? 'desc' : 'asc']); @endphp
                    <th scope="col">
                        <a href="{{ route('fornecedores.index', array_merge($linkParamsNomeFantasia, ['sort_by' => 'nome_fantasia'])) }}">
                            Nome Fantasia
                            @if($sortBy == 'nome_fantasia') <i class="bi bi-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i> @endif
                        </a>
                    </th>
                    
                    <th scope="col">Telefones</th>
                    <th scope="col">E-mails</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fornecedores as $fornecedor)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if ($fornecedor->status === 'ativo')
                                    <span class="badge rounded-pill bg-success me-2" title="Ativo">&nbsp;</span>
                                @elseif ($fornecedor->status === 'em_analise')
                                    <span class="badge rounded-pill bg-warning me-2" title="Em Análise">&nbsp;</span>
                                @else
                                    <span class="badge rounded-pill bg-danger me-2" title="{{ ucfirst($fornecedor->status) }}">&nbsp;</span>
                                @endif
                                <div>
                                    <strong>{{ $fornecedor->razao_social }}</strong>
                                    <br>
                                    <small class="text-muted">CNPJ: {{ $fornecedor->cnpj }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $fornecedor->nome_fantasia ?: '--' }}</td>
                        <td>
                            {!! implode('<br>', array_filter([$fornecedor->telefone, $fornecedor->telefone2])) ?: '--' !!}
                        </td>
                        <td>
                            {!! implode('<br>', array_filter([$fornecedor->email, $fornecedor->email2])) ?: '--' !!}
                        </td>
                        <td class="text-center">
                            @if ($fornecedor->trashed())
                                @can('restore', $fornecedor)
                                <form action="{{ route('fornecedores.restore', $fornecedor) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="Restaurar"><i class="bi bi-arrow-counterclockwise"></i></button>
                                </form>
                                @endcan
                            @else
                                @can('update', $fornecedor)
                                <a href="{{ route('fornecedores.edit', $fornecedor) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                @endcan
                                @can('delete', $fornecedor)
                                <form action="{{ route('fornecedores.destroy', $fornecedor) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja desativar este fornecedor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Desativar"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Nenhum fornecedor encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $fornecedores->appends(request()->query())->links() }}
    </div>
@endsection