<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ========== LIMPEZA ==========

        Schema::dropIfExists('categorias');
        Schema::dropIfExists('_funcionarios');
        Schema::dropIfExists('_usuarios_sistema');

        // Completar _oficina com colunas que faltam
        Schema::table('_oficina', function (Blueprint $table) {
            $table->string('nome')->nullable();
            $table->text('descricao')->nullable();
            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('data_fim')->nullable();
            $table->foreignId('id_instrutor')->nullable()->constrained('instrutores', 'id_instrutor')->nullOnDelete();
        });

        // Completar _inscricoes_oficina com colunas que faltam
        Schema::table('_inscricoes_oficina', function (Blueprint $table) {
            $table->foreignId('id_cliente')->nullable()->constrained('_cliente', 'id_cliente')->cascadeOnDelete();
            $table->foreignId('id_oficina')->nullable()->constrained('_oficina', 'id')->cascadeOnDelete();
            $table->dateTime('data_inscricao')->nullable();
        });

        // ========== PERFIL DO ARTESÃO ==========

        Schema::create('artisan_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->string('phone', 20)->nullable();
            $table->string('specialty', 100)->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('facebook', 100)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artisan_profiles');

        Schema::table('_inscricoes_oficina', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropForeign(['id_oficina']);
            $table->dropColumn(['id_cliente', 'id_oficina', 'data_inscricao']);
        });

        Schema::table('_oficina', function (Blueprint $table) {
            $table->dropForeign(['id_instrutor']);
            $table->dropColumn(['nome', 'descricao', 'data_inicio', 'data_fim', 'id_instrutor']);
        });

        Schema::create('_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('_usuarios_sistema', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome_categoria');
            $table->timestamps();
        });
    }
};
