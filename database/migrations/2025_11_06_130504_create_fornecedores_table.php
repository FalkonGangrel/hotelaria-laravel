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
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id(); // Cria uma coluna 'id' auto-incremento e chave primária.
            $table->string('nome'); // Coluna para o nome do fornecedor
            $table->string('cnpj')->unique(); // Coluna para o CNPJ, valor deve ser único
            $table->string('email')->unique(); // Coluna para o e-mail, valor deve ser único
            $table->string('telefone')->nullable(); // Coluna para telefone, pode ser nulo
            $table->timestamps(); // Cria as colunas 'created_at' e 'updated_at' automaticamente
            $table->softDeletes(); // Adiciona a coluna 'deleted_at' para soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornecedores');
    }
};
