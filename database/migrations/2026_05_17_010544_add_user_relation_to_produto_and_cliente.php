<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('_cliente', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('produto', function (Blueprint $table) {
            $table->foreignId('id_artesan')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('produto', function (Blueprint $table) {
            $table->dropForeign(['id_artesan']);
            $table->dropColumn('id_artesan');
        });

        Schema::table('_cliente', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
