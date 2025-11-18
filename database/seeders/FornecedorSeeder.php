<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fornecedor;

class FornecedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fornecedor::create([
            'razao_social' => 'Razão Fornecedor Exemplo A',
            'nome_fantasia' => 'Fornecedor Exemplo A',
            'cnpj' => '11.111.111/0001-11',
            'email' => 'contato@fornecedora.com',
            'telefone' => '00 1234-5678',
        ]);

        Fornecedor::create([
            'razao_social' => 'Razão Distribuidora de Suprimentos B',
            'nome_fantasia' => 'Distribuidora de Suprimentos B',
            'cnpj' => '22.222.222/0001-22',
            'email' => 'vendas@distribuidorab.com',
            'telefone' => '00 1234-5679',
        ]);
    }
}
