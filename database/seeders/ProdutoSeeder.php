<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produto::create(['nome' => 'Produto Teste 1', 'preco_venda' => 19.99, 'quantidade_estoque' => 100, 'fornecedor_id' => 1]);
        Produto::create(['nome' => 'Produto Teste 2', 'preco_venda' => 25.50, 'quantidade_estoque' => 50, 'fornecedor_id' => 1]);
        Produto::create(['nome' => 'Produto Teste 3', 'preco_venda' => 9.90, 'quantidade_estoque' => 200, 'fornecedor_id' => 2]);
        Produto::create(['nome' => 'Produto Teste 4', 'preco_venda' => 150.00, 'quantidade_estoque' => 20, 'fornecedor_id' => 2]);
        Produto::create(['nome' => 'Produto Teste 5', 'preco_venda' => 75.25, 'quantidade_estoque' => 80, 'fornecedor_id' => 1]);
        Produto::create(['nome' => 'Produto Teste 6', 'preco_venda' => 42.00, 'quantidade_estoque' => 120, 'fornecedor_id' => 2]);
    }
}
