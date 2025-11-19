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

            // --- Identificação do Produto ---
            $table->string('nome');
            $table->string('sku')->nullable()->unique()->comment('Stock Keeping Unit');
            $table->string('barcode')->nullable()->unique()->comment('Código de Barras (EAN)');

            // --- Descrição e Categoria ---
            $table->text('descricao')->nullable();
            $table->string('unidade_medida', 10)->default('un');

            // --- Detalhes Financeiros ---
            $table->decimal('preco_custo', 10, 2)->default(0.00);
            $table->decimal('preco_venda', 10, 2)->default(0.00);

            // --- Controle de Estoque (com precisão decimal) ---
            $table->decimal('quantidade_estoque', 10, 3)->default(0.000); // 3 casas para precisão em kg, etc.
            $table->decimal('estoque_minimo', 10, 3)->default(0.000);

            // --- Status e Relacionamentos ---
            $table->enum('status', ['ativo', 'inativo', 'sem estoque', 'em pedido'])->default('ativo');
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
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