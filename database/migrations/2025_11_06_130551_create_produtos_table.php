<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('preco_venda', 10, 2); // Preço com 10 dígitos no total e 2 casas decimais

            // --- NOSSAS MELHORIAS DE ESTOQUE ---
            $table->integer('quantidade_estoque')->default(0);
            $table->integer('estoque_minimo')->default(0)->nullable();

            // --- CHAVE ESTRANGEIRA ---
            // Isso cria a coluna 'fornecedor_id' e a relação com a tabela 'fornecedores'
            $table->foreignId('fornecedor_id')->constrained('fornecedores');

            $table->timestamps();

            $table->softDeletes(); // Adiciona a coluna 'deleted_at' para soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
