<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias_produtos', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('categorias_produtos', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('nome_categoria')
                ->constrained('categorias_produtos', 'id_categoria')
                ->nullOnDelete();
        });
    }
};
