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
            $table->id();
            $table->string('razao_social'); // Razão Social do fornecedor
            $table->string('nome_fantasia')->nullable(); // Nome Fantasia do fornecedor
            $table->string('cnpj')->unique();
            $table->string('ie')->nullable(); // Inscrição Estadual
            $table->string('email')->unique(); // E-mail principal
            $table->string('email2')->nullable(); // E-mail comercial/administrativo
            $table->string('telefone')->nullable();
            $table->string('telefone2')->nullable(); // Telefone adicional

            // --- CAMPOS DE ENDEREÇO ESTRUTURADO (ADICIONADOS) ---
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable(); // UF com 2 caracteres (ex: SP, RJ)
            $table->string('cep')->nullable();

            // --- CAMPO DE STATUS (ADICIONADO) ---
            // 'ativo', 'inativo', 'em_analise', 'suspenso'
            $table->string('status')->default('ativo');

            // --- CAMPO DE OBSERVAÇÕES INTERNAS (ADICIONADO) ---
            $table->text('observacoes')->nullable();

            // --- VÍNCULO COM A CONTA DE USUÁRIO (ADICIONADO) ---
            // Este campo conecta o fornecedor a um registro na tabela 'users'.
            // O usuário vinculado terá a 'role' de 'fornecedor'.
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
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