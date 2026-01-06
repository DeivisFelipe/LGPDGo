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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('protocolo')->unique()->comment('Protocolo único gerado automaticamente');
            $table->enum('tipo', ['exclusao', 'consulta', 'portabilidade', 'correcao', 'oposicao', 'revogacao_consentimento']);
            $table->string('nome_titular');
            $table->string('email_titular');
            $table->string('telefone_titular')->nullable();
            $table->string('documento_titular')->nullable()->comment('CPF ou outro documento');
            $table->text('detalhes')->nullable();
            $table->enum('status', ['aberto', 'em_andamento', 'aguardando_titular', 'concluido', 'cancelado'])->default('aberto');
            $table->text('resposta')->nullable();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('prazo_legal')->nullable()->comment('15 dias corridos conforme LGPD');
            $table->timestamp('data_conclusao')->nullable();
            $table->boolean('verificado')->default(false)->comment('Se a identidade foi verificada');
            $table->string('codigo_verificacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
