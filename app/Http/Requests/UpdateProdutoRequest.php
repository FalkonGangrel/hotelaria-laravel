<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('produto'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $produtoId = $this->route('produto')->id;

        // REGRA DE NEGÓCIO: Fornecedor só pode editar estes campos
        if ($this->user()->role === 'fornecedor') {
            return [
                'nome' => ['required', 'string', 'max:255'],
                'sku' => ['nullable', 'string', 'max:255', Rule::unique('produtos', 'sku')->ignore($produtoId)],
                'barcode' => ['nullable', 'string', 'max:255', Rule::unique('produtos', 'barcode')->ignore($produtoId)],
                'descricao' => ['nullable', 'string'],
                'preco_custo' => ['required', 'numeric', 'min:0'],
            ];
        }

        // Regras completas para admin/master
        return [
            'nome' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('produtos', 'sku')->ignore($produtoId)],
            'barcode' => ['nullable', 'string', 'max:255', Rule::unique('produtos', 'barcode')->ignore($produtoId)],
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
