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
        Schema::create('cookies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->text('descricao');
            $table->enum('categoria', ['necessario', 'funcional', 'analitico', 'marketing', 'publicidade']);
            $table->string('provedor')->nullable();
            $table->string('duracao')->nullable()->comment('Ex: Sessão, 1 ano, 30 dias');
            $table->text('finalidade');
            $table->string('script_path')->nullable()->comment('Caminho para o script do cookie');
            $table->boolean('requer_consentimento')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cookies');
    }
};
