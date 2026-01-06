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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('data_inventory_id')->nullable()->constrained()->onDelete('set null');
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('probabilidade', ['muito_baixa', 'baixa', 'media', 'alta', 'muito_alta']);
            $table->enum('impacto', ['muito_baixo', 'baixo', 'medio', 'alto', 'muito_alto']);
            $table->enum('nivel_risco', ['baixo', 'medio', 'alto', 'critico'])->comment('Calculado automaticamente');
            $table->text('plano_acao')->nullable();
            $table->enum('status', ['identificado', 'em_tratamento', 'mitigado', 'aceito'])->default('identificado');
            $table->date('data_revisao')->nullable();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
