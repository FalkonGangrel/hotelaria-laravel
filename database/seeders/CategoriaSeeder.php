<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use Illuminate\Support\Str; // <-- IMPORTANTE: Precisamos importar o helper Str

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- CRIAÇÃO DAS CATEGORIAS-PAI ---
        // 1. Criamos a categoria pai e a armazenamos em uma variável.
        $categoriaA = Categoria::create([
            'nome' => 'Eletrônicos',
            'slug' => Str::slug('Eletrônicos'), // 2. O slug é gerado dinamicamente.
        ]);

        $categoriaB = Categoria::create([
            'nome' => 'Roupas e Acessórios',
            'slug' => Str::slug('Roupas e Acessórios'),
        ]);

        // --- CRIAÇÃO DAS SUB-CATEGORIAS ---
        // Agora, usamos os IDs das variáveis que acabamos de criar.
        Categoria::create([
            'nome' => 'Smartphones',
            'slug' => Str::slug('Smartphones'),
            'parent_id' => $categoriaA->id, // 3. Usamos o ID dinâmico, não um número fixo.
        ]);

        Categoria::create([
            'nome' => 'Notebooks',
            'slug' => Str::slug('Notebooks'),
            'parent_id' => $categoriaA->id, // Também é filha de Eletrônicos
        ]);

        Categoria::create([
            'nome' => 'Camisetas',
            'slug' => Str::slug('Camisetas'),
            'parent_id' => $categoriaB->id,
        ]);
    }
}