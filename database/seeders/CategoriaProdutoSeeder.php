<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;
use App\Models\Categoria;

class CategoriaProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Busque os produtos e categorias que você quer conectar.
        // É mais seguro buscar por um campo único, como o nome ou o slug.
        $produtoSmartphone = Produto::where('sku', 'SP-PRO-MAX')->first();
        $produtoNotebook = Produto::where('sku', 'NB-GAMER-ULTRA')->first();
        $produtoCamiseta = Produto::where('sku', 'CM-BASICA-ALG')->first();

        $categoriaEletronicos = Categoria::where('slug', 'eletronicos')->first();
        $categoriaRoupas = Categoria::where('slug', 'roupas-e-acessorios')->first();
        $subCategoriaSmartphones = Categoria::where('slug', 'smartphones')->first();
        $subCategoriaNotebooks = Categoria::where('slug', 'notebooks')->first();
        $subCategoriaCamisetas = Categoria::where('slug', 'camisetas')->first();

        // Verifique se os registros foram encontrados antes de tentar conectar
        if ($produtoSmartphone && $categoriaEletronicos && $subCategoriaSmartphones) {
            // 2. Use o método attach() para criar o registro na tabela pivot.
            // Você pode anexar a múltiplas categorias de uma vez passando um array de IDs.
            $produtoSmartphone->categorias()->attach([
                $categoriaEletronicos->id,
                $subCategoriaSmartphones->id
            ]);
        }

        if ($produtoNotebook && $categoriaEletronicos && $subCategoriaNotebooks) {
            $produtoNotebook->categorias()->attach([
                $categoriaEletronicos->id,
                $subCategoriaNotebooks->id
            ]);
        }
        
        if ($produtoCamiseta && $categoriaRoupas && $subCategoriaCamisetas) {
            $produtoCamiseta->categorias()->attach([
                $categoriaRoupas->id,
                $subCategoriaCamisetas->id
            ]);
        }
    }
}