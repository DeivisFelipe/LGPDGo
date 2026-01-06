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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('cnpj');
            $table->integer('score_adequacao')->default(0)->comment('Score de adequação à LGPD (0-100)');
            $table->boolean('status_selo')->default(false)->comment('Se a empresa possui selo de adequação');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['logo', 'score_adequacao', 'status_selo']);
        });
    }
};
