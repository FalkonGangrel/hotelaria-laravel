@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
    <h1>Editar Produto: {{ $produto->nome }}</h1>
    <hr>

    <form action="{{ route('produtos.update', $produto) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Variável auxiliar para limpar o código --}}
        @php
            $isFornecedor = auth()->user()->role === 'fornecedor';
        @endphp

        {{-- Card de Informações Básicas --}}
        <div class="card mb-3">
            <div class="card-header">Informações Básicas</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="nome" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                        {{-- Fornecedor PODE editar --}}
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
                        @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fornecedor_id" class="form-label">Fornecedor <span class="text-danger">*</span></label>
                        {{-- Fornecedor NÃO PODE editar --}}
                        <select class="form-select @error('fornecedor_id') is-invalid @enderror" id="fornecedor_id" name="fornecedor_id" required @if($isFornecedor) disabled @endif>
                            @foreach ($fornecedores as $fornecedor)
                                <option value="{{ $fornecedor->id }}" {{ old('fornecedor_id', $produto->fornecedor_id) == $fornecedor->id ? 'selected' : '' }}>{{ $fornecedor->razao_social }}</option>
                            @endforeach
                        </select>
                        @error('fornecedor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    {{-- Fornecedor PODE editar --}}
                    <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3">{{ old('descricao', $produto->descricao) }}</textarea>
                    @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Card de Códigos e Unidades --}}
        <div class="card mb-3">
            <div class="card-header">Códigos e Unidades</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="sku" class="form-label">SKU (Código Interno)</label>
                        {{-- Fornecedor PODE editar --}}
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $produto->sku) }}">
                        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="barcode" class="form-label">Código de Barras (EAN)</label>
                        {{-- Fornecedor PODE editar --}}
                        <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" value="{{ old('barcode', $produto->barcode) }}">
                        @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="unidade_medida" class="form-label">Unidade de Medida <span class="text-danger">*</span></label>
                        {{-- Fornecedor NÃO PODE editar --}}
                        <select class="form-select @error('unidade_medida') is-invalid @enderror" id="unidade_medida" name="unidade_medida" required @if($isFornecedor) readonly disabled @endif>
                            <option value="un" {{ old('unidade_medida', $produto->unidade_medida) == 'un' ? 'selected' : '' }}>Unidade (UN)</option>
                            <option value="kg" {{ old('unidade_medida', $produto->unidade_medida) == 'kg' ? 'selected' : '' }}>Kilograma (KG)</option>
                            <option value="l" {{ old('unidade_medida', $produto->unidade_medida) == 'l' ? 'selected' : '' }}>Litro (L)</option>
                            <option value="m" {{ old('unidade_medida', $produto->unidade_medida) == 'm' ? 'selected' : '' }}>Metro (M)</option>
                            <option value="pç" {{ old('unidade_medida', $produto->unidade_medida) == 'pç' ? 'selected' : '' }}>Peça (PÇ)</option>
                        </select>
                        @error('unidade_medida') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card de Valores e Estoque --}}
        <div class="card mb-3">
            <div class="card-header">Valores e Estoque</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="preco_custo" class="form-label">Preço de Custo <span class="text-danger">*</span></label>
                        {{-- Fornecedor PODE editar --}}
                        <input type="number" step="0.01" class="form-control @error('preco_custo') is-invalid @enderror" id="preco_custo" name="preco_custo" value="{{ old('preco_custo', $produto->preco_custo) }}" required>
                        @error('preco_custo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    {{-- REGRA DE NEGÓCIO: Fornecedor NÃO PODE ver o preço de venda --}}
                    @if(!$isFornecedor)
                        <div class="col-md-3 mb-3">
                            <label for="preco_venda" class="form-label">Preço de Venda <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('preco_venda') is-invalid @enderror" id="preco_venda" name="preco_venda" value="{{ old('preco_venda', $produto->preco_venda) }}" required>
                            @error('preco_venda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endif

                    {{-- Fornecedor NÃO PODE editar estoque --}}
                    <div class="col-md-3 mb-3">
                        <label for="quantidade_estoque" class="form-label">Estoque Atual <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantidade_estoque') is-invalid @enderror" id="quantidade_estoque" name="quantidade_estoque" value="{{ old('quantidade_estoque', $produto->quantidade_estoque) }}" required @if($isFornecedor) readonly @endif>
                        @error('quantidade_estoque') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="estoque_minimo" class="form-label">Estoque Mínimo</label>
                        <input type="number" class="form-control @error('estoque_minimo') is-invalid @enderror" id="estoque_minimo" name="estoque_minimo" value="{{ old('estoque_minimo', $produto->estoque_minimo) }}" @if($isFornecedor) readonly @endif>
                        @error('estoque_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Status e Botões --}}
        {{-- Fornecedor NÃO PODE editar o status --}}
        @if(!$isFornecedor)
            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="ativo" {{ old('status', $produto->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ old('status', $produto->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                    <option value="sem_estoque" {{ old('status', $produto->status) == 'sem_estoque' ? 'selected' : '' }}>Sem Estoque</option>
                    <option value="em_pedido" {{ old('status', $produto->status) == 'em_pedido' ? 'selected' : '' }}>Em Pedido</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        @endif

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Atualizar Produto</button>
        </div>
    </form>
@endsection