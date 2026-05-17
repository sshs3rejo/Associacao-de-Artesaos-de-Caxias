<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('_eventos', function (Blueprint $table) {
            $table->boolean('is_approved')->default(true);
            $table->foreignId('id_artesan')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('_eventos', function (Blueprint $table) {
            $table->dropForeign(['id_artesan']);
            $table->dropColumn(['is_approved', 'id_artesan']);
        });
    }
};
