<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('_cliente', function (Blueprint $table) {
            $table->string('telefone')->nullable()->change();
            $table->text('endereco')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('_cliente', function (Blueprint $table) {
            $table->string('telefone')->nullable(false)->change();
            $table->text('endereco')->nullable(false)->change();
        });
    }
};
