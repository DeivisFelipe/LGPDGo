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
        Schema::table('requests', function (Blueprint $table) {
            // Adicionar novos campos usados pelo controller
            $table->string('tipo_solicitacao')->nullable()->after('protocolo');
            $table->string('email')->nullable()->after('nome_titular');
            $table->string('telefone')->nullable()->after('email');
            $table->string('cpf')->nullable()->after('telefone');
            $table->text('descricao')->nullable()->after('cpf');
            $table->string('preferencia_contato')->nullable()->after('descricao');
            $table->timestamp('prazo_resposta')->nullable()->after('resposta');
            $table->text('observacoes_internas')->nullable()->after('prazo_resposta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_solicitacao',
                'email',
                'telefone',
                'cpf',
                'descricao',
                'preferencia_contato',
                'prazo_resposta',
                'observacoes_internas',
            ]);
        });
    }
};
