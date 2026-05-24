<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produto', function (Blueprint $table) {
            $table->index('id_categoria', 'idx_produto_id_categoria');
            $table->index('is_approved', 'idx_produto_is_approved');
        });

        Schema::table('_vendas', function (Blueprint $table) {
            $table->index('id_cliente', 'idx_vendas_id_cliente');
        });

        Schema::table('itens_venda', function (Blueprint $table) {
            $table->index('id_venda', 'idx_itens_venda_id_venda');
            $table->index('id_produto', 'idx_itens_venda_id_produto');
        });

        Schema::table('_eventos', function (Blueprint $table) {
            $table->index('id_instrutor', 'idx_eventos_id_instrutor');
            $table->index('is_approved', 'idx_eventos_is_approved');
            $table->index('status', 'idx_eventos_status');
            $table->index('data_inicio', 'idx_eventos_data_inicio');
        });

        Schema::table('_inscricoes_evento', function (Blueprint $table) {
            $table->index('id_cliente', 'idx_inscricoes_evento_id_cliente');
            $table->index('id_evento', 'idx_inscricoes_evento_id_evento');
        });

        Schema::table('compras_materia_prima', function (Blueprint $table) {
            $table->index('id_fornecedor', 'idx_compras_id_fornecedor');
            $table->index('id_materia', 'idx_compras_id_materia');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
        });
    }

    public function down(): void
    {
        Schema::table('produto', function (Blueprint $table) {
            $table->dropIndex('idx_produto_id_categoria');
            $table->dropIndex('idx_produto_is_approved');
        });

        Schema::table('_vendas', function (Blueprint $table) {
            $table->dropIndex('idx_vendas_id_cliente');
        });

        Schema::table('itens_venda', function (Blueprint $table) {
            $table->dropIndex('idx_itens_venda_id_venda');
            $table->dropIndex('idx_itens_venda_id_produto');
        });

        Schema::table('_eventos', function (Blueprint $table) {
            $table->dropIndex('idx_eventos_id_instrutor');
            $table->dropIndex('idx_eventos_is_approved');
            $table->dropIndex('idx_eventos_status');
            $table->dropIndex('idx_eventos_data_inicio');
        });

        Schema::table('_inscricoes_evento', function (Blueprint $table) {
            $table->dropIndex('idx_inscricoes_evento_id_cliente');
            $table->dropIndex('idx_inscricoes_evento_id_evento');
        });

        Schema::table('compras_materia_prima', function (Blueprint $table) {
            $table->dropIndex('idx_compras_id_fornecedor');
            $table->dropIndex('idx_compras_id_materia');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
        });
    }
};
