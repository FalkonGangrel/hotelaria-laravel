@extends('layouts.app')

@section('title', 'Cadastrar Novo Fornecedor')

@section('content')
    <h1>Cadastrar Novo Fornecedor</h1>
    <hr>

    <form action="{{ route('fornecedores.store') }}" method="POST">
        @csrf

        {{-- Alerta para o usuário sobre a funcionalidade --}}
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">Preenchimento Automático!</h4>
            <p>Digite um CNPJ válido e clique fora do campo para buscar os dados da empresa. Da mesma forma, digite um CEP para preencher o endereço.</p>
        </div>

        {{-- Seção Dados da Empresa --}}
        <div class="card mb-3">
            <div class="card-header">Dados da Empresa</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="cnpj" class="form-label">CNPJ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cnpj') is-invalid @enderror" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" required>
                        <small class="form-text text-muted">Digite apenas os números.</small>
                        @error('cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="razao_social" class="form-label">Razão Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('razao_social') is-invalid @enderror" id="razao_social" name="razao_social" value="{{ old('razao_social') }}" required>
                        @error('razao_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                        <input type="text" class="form-control @error('nome_fantasia') is-invalid @enderror" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia') }}">
                        @error('nome_fantasia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ie" class="form-label">Inscrição Estadual</label>
                        <input type="text" class="form-control @error('ie') is-invalid @enderror" id="ie" name="ie" value="{{ old('ie') }}">
                        @error('ie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="ativo" {{ old('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                            <option value="em_analise" {{ old('status') == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                            <option value="suspenso" {{ old('status') == 'suspenso' ? 'selected' : '' }}>Suspenso</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Seção Contato --}}
        <div class="card mb-3">
            <div class="card-header">Contato</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">E-mail Principal <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="email2" class="form-label">E-mail Secundário</label>
                        <input type="email" class="form-control @error('email2') is-invalid @enderror" id="email2" name="email2" value="{{ old('email2') }}">
                        @error('email2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="telefone" class="form-label">Telefone Principal</label>
                        <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}">
                        @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="telefone2" class="form-label">Telefone Secundário</label>
                        <input type="text" class="form-control @error('telefone2') is-invalid @enderror" id="telefone2" name="telefone2" value="{{ old('telefone2') }}">
                        @error('telefone2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Seção Endereço --}}
        <div class="card mb-3">
            <div class="card-header">Endereço</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep') }}">
                        <small class="form-text text-muted">Digite e saia do campo para buscar.</small>
                        @error('cep')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-7 mb-3">
                        <label for="logradouro" class="form-label">Logradouro</label>
                        <input type="text" class="form-control @error('logradouro') is-invalid @enderror" id="logradouro" name="logradouro" value="{{ old('logradouro') }}">
                        @error('logradouro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero') }}">
                        @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control @error('complemento') is-invalid @enderror" id="complemento" name="complemento" value="{{ old('complemento') }}">
                        @error('complemento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro') }}">
                        @error('bairro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade') }}">
                        @error('cidade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-1 mb-3">
                        <label for="uf" class="form-label">UF</label>
                        <input type="text" class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf" value="{{ old('uf') }}">
                        @error('uf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Seção Observações --}}
        <div class="card mb-3">
            <div class="card-header">Informações Adicionais</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações Internas</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
                    <small class="form-text text-muted">Estas observações são para seu controle interno e não serão vistas pelo fornecedor.</small>
                </div>
            </div>
        </div>


        <hr>
        <button type="submit" class="btn btn-primary">Cadastrar Fornecedor</button>
        <a href="{{ route('fornecedores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const masks = {};

        function preencherFormulario(data, manualMapping = {}, force = false) {

            function preencherCampo(fieldId, value) {

                const field = document.getElementById(fieldId);
                if (!field) return;

                // Se existe máscara
                if (masks[fieldId]) {
                    masks[fieldId].value = value;
                    return;
                }

                // Campo normal
                field.value = value;
            }

            // Loop automático (data.key -> campo com mesmo id)
            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    preencherCampo(key, data[key]);
                }
            }

            // Loop dos mapeamentos obrigatórios
            for (const apiKey in manualMapping) {
                if (data.hasOwnProperty(apiKey)) {
                    preencherCampo(manualMapping[apiKey], data[apiKey]);
                }
            }
        }

        // --- MÁSCARAS ---
        masks['cnpj'] = IMask(document.getElementById('cnpj'), { mask: '00.000.000/0000-00' });
        masks['cep'] = IMask(document.getElementById('cep'), { mask: '00000-000' });
        masks['telefone'] = IMask(document.getElementById('telefone'), { mask: '(00) 00000-0000' });
        masks['telefone2'] = IMask(document.getElementById('telefone2'), { mask: '(00) 00000-0000' });

        // --- BUSCA CNPJ ---
        document.getElementById('cnpj').addEventListener('blur', function() {
            const cnpj = masks['cnpj'].unmaskedValue;

            if (cnpj.length === 14) {
                fetch(`https://brasilapi.com.br/api/cnpj/v1/${cnpj}`)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.cnpj) throw new Error("CNPJ inválido.");

                        preencherFormulario(data, {
                            municipio: 'cidade',
                            ddd_telefone_1: 'telefone'
                        });

                        document.getElementById('numero').focus();
                    })
                    .catch(err => alert(err.message));
            }
        });

        // --- BUSCA CEP ---
        document.getElementById('cep').addEventListener('blur', function() {
            const cep = masks['cep'].unmaskedValue;

            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.erro) throw new Error("CEP não encontrado.");

                        preencherFormulario(data, {
                            localidade: 'cidade'
                        });

                        document.getElementById('numero').focus();
                    })
                    .catch(err => alert(err.message));
            }
        });

    });
    </script>
    @endpush


@endsection