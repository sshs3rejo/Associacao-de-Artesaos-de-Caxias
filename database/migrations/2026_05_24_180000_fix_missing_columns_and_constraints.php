<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('_oficina', function (Blueprint $table) {
            $table->decimal('carga_horaria', 5, 1)->nullable()->after('descricao');
            $table->string('horario', 255)->nullable()->after('data_fim');
            $table->string('local', 255)->nullable()->after('horario');
            $table->integer('vagas')->nullable()->after('local');
        });

        Schema::table('compras_materia_prima', function (Blueprint $table) {
            $table->decimal('preco_unitario', 8, 2)->nullable()->after('quantidade');
        });

        Schema::table('instrutores', function (Blueprint $table) {
            $table->string('telefone', 20)->nullable()->after('nome');
            $table->text('biografia')->nullable()->after('especialidade');
            $table->string('foto', 255)->nullable()->after('biografia');
        });

        Schema::table('_fornecedores', function (Blueprint $table) {
            $table->text('endereco')->nullable()->after('email');
        });

        Schema::table('_estoques', function (Blueprint $table) {
            $table->dropForeign(['id_produto']);
            $table->foreign('id_produto')->references('id_produto')->on('produto')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('_estoques', function (Blueprint $table) {
            $table->dropForeign(['id_produto']);
            $table->foreign('id_produto')->references('id_produto')->on('produto');
        });

        Schema::table('_fornecedores', function (Blueprint $table) {
            $table->dropColumn('endereco');
        });

        Schema::table('instrutores', function (Blueprint $table) {
            $table->dropColumn(['telefone', 'biografia', 'foto']);
        });

        Schema::table('compras_materia_prima', function (Blueprint $table) {
            $table->dropColumn('preco_unitario');
        });

        Schema::table('_oficina', function (Blueprint $table) {
            $table->dropColumn(['carga_horaria', 'horario', 'local', 'vagas']);
        });
    }
};
