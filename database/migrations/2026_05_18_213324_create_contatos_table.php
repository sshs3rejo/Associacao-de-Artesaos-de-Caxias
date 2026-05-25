<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
                if (!Schema::hasTable('contatos')) {
                    Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->string('email', 255);
            $table->text('mensagem');
            $table->boolean('lido')->default(false);
            $table->timestamps();
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contatos');
    }
};
