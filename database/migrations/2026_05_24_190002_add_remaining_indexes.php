<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('_vendas', function (Blueprint $table) {
            $table->index('data_venda');
        });

        Schema::table('_inscricoes_evento', function (Blueprint $table) {
            $table->index('data_inscricao');
        });

        Schema::table('compras_materia_prima', function (Blueprint $table) {
            $table->index('data_compra');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('produto', function (Blueprint $table) {
            $table->index('id_artesan');
        });
    }

    public function down(): void
    {
        Schema::table('produto', function (Blueprint $table) {
            $table->dropIndex(['id_artesan']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
        Schema::table('compras_materia_prima', function (Blueprint $table) {
            $table->dropIndex(['data_compra']);
        });
        Schema::table('_inscricoes_evento', function (Blueprint $table) {
            $table->dropIndex(['data_inscricao']);
        });
        Schema::table('_vendas', function (Blueprint $table) {
            $table->dropIndex(['data_venda']);
        });
    }
};
