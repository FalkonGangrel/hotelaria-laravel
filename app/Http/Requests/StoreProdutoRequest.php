<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Usa a ProdutoPolicy para decidir. Apenas admin/master podem criar.
        return $this->user()->can('create', \App\Models\Produto::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Regras completas para admin/master
        return [
            'nome' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('produtos', 'sku')],
            'barcode' => ['nullable', 'string', 'max:255', Rule::unique('produtos', 'barcode')],
            'descricao' => ['nullable','string'],
            'unidade_medida' => ['required', 'string', 'max:10'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
            'preco_venda' => ['required', 'numeric', 'min:0'],
            'quantidade_estoque' => ['required', 'numeric', 'min:0'],
            'estoque_minimo' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', Rule::in(['ativo', 'inativo', 'sem estoque', 'em pedido'])],
            'fornecedor_id' => ['required', Rule::exists('fornecedores', 'id')],
        ];
    }
}
