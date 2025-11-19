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
        Schema::create('categoria_produto', function (Blueprint $table) {
            // A convenção do Laravel não usa 'id' para tabelas pivot.
            
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');

            // Definimos uma chave primária composta para evitar duplicatas (mesmo produto na mesma categoria).
            $table->primary(['categoria_id', 'produto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_produto');
    }
};
