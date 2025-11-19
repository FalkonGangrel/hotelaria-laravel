<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fornecedor; // Importante
use App\Models\Produto;
use Illuminate\Support\Str;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Encontre o primeiro fornecedor que existe no banco para associar.
        $fornecedor = Fornecedor::first();

        // 2. Crie alguns produtos se houver fornecedor.
        if(!$fornecedor) {
            return; // Sai se não houver fornecedor
        }

        Produto::create([
            'fornecedor_id' => $fornecedor->id,
            'nome' => 'Smartphone Pro Max',
            'descricao' => 'O último lançamento em tecnologia móvel.',
            'sku' => 'SP-PRO-MAX',
            'preco_custo' => 3500.00,
            'preco_venda' => 5999.99,
            'quantidade_estoque' => 50,
            'estoque_minimo' => 10,
            'unidade_medida' => 'un'
        ]);
        
        Produto::create([
            'fornecedor_id' => $fornecedor->id,
            'nome' => 'Notebook Gamer Ultra',
            'descricao' => 'Notebook de alta performance para jogos e trabalho.',
            'sku' => 'NB-GAMER-ULTRA',
            'preco_custo' => 6000.00,
            'preco_venda' => 9500.00,
            'quantidade_estoque' => 20,
            'estoque_minimo' => 5,
            'unidade_medida' => 'un'
        ]);

        Produto::create([
            'fornecedor_id' => $fornecedor->id,
            'nome' => 'Camiseta Básica de Algodão',
            'descricao' => 'Camiseta confortável para o dia a dia.',
            'sku' => 'CM-BASICA-ALG',
            'preco_custo' => 25.00,
            'preco_venda' => 79.90,
            'quantidade_estoque' => 200,
            'estoque_minimo' => 20,
            'unidade_medida' => 'pç'
        ]);
    }
}