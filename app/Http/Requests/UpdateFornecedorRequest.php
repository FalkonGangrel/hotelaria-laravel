<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFornecedorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // O método route() pega o parâmetro da rota, neste caso {fornecedor}.
        $fornecedor = $this->route('fornecedor');
        
        // Reutiliza a mesma lógica da sua FornecedorPolicy.
        return $this->user()->can('update', $fornecedor);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $fornecedor = $this->route('fornecedor');

        if ($this->user()->role === 'fornecedor') {
            return [
                'razao_social' => ['required', 'string', 'max:255'],
                'nome_fantasia' => ['nullable', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('fornecedores', 'email')->ignore($fornecedor->id)],
                'email2' => ['nullable', 'email', 'max:255'],
                'telefone' => ['nullable', 'string', 'max:20'],
                'telefone2' => ['nullable', 'string', 'max:20'],
                'logradouro' => ['nullable', 'string', 'max:255'],
                'numero' => ['nullable', 'string', 'max:50'],
                'complemento' => ['nullable', 'string', 'max:255'],
                'bairro' => ['nullable', 'string', 'max:100'],
                'cidade' => ['nullable', 'string', 'max:100'],
                'uf' => ['nullable', 'string', 'max:2'],
                'cep' => ['nullable', 'string', 'max:9'],
            ];
        }

        // Regras para admin/master
        return [
            'razao_social' => ['required', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', Rule::unique('fornecedores', 'cnpj')->ignore($fornecedor->id)],
            'ie' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('fornecedores', 'email')->ignore($fornecedor->id)],
            'email2' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'telefone2' => ['nullable', 'string', 'max:20'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:50'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:100'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'uf' => ['nullable', 'string', 'max:2'],
            'cep' => ['nullable', 'string', 'max:9'],
            'status' => ['required', 'string', Rule::in(['ativo', 'inativo', 'em_analise', 'suspenso'])],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}
