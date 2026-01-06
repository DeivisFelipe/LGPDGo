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
        Schema::create('data_inventories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nome_processo');
            $table->text('finalidade');
            $table->enum('base_legal', ['consentimento', 'contrato', 'obrigacao_legal', 'legitimo_interesse', 'protecao_da_vida', 'exercicio_regular_direitos']);
            $table->json('categoria_dados')->comment('Array de categorias: pessoais, sensiveis, financeiros, etc');
            $table->string('tempo_retencao')->comment('Ex: 5 anos, Indeterminado');
            $table->json('quem_acessa')->comment('Array de departamentos/pessoas com acesso');
            $table->text('medidas_seguranca');
            $table->json('origem_dados')->nullable()->comment('De onde vem os dados');
            $table->json('destinatarios_dados')->nullable()->comment('Com quem compartilhamos');
            $table->boolean('transferencia_internacional')->default(false);
            $table->string('pais_destino')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_inventories');
    }
};
