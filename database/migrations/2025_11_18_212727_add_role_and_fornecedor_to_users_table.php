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
        Schema::table('users', function (Blueprint $table) {
            // Adiciona as colunas após a coluna 'photo_path' para organização
            $table->after('photo_path', function ($table) {
                $table->enum('role', ['master', 'admin', 'user', 'cliente', 'fornecedor'])->default('cliente');
                $table->foreignId('fornecedor_id')->nullable()->constrained('fornecedores')->onDelete('set null');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove na ordem inversa para evitar erros de chave estrangeira
            $table->dropForeign(['fornecedor_id']);
            $table->dropColumn(['role', 'fornecedor_id']);
        });
    }
};