<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('_vendas', function (Blueprint $table) {
            $table->renameColumn('mp_status', 'status_pagamento');
            $table->dropColumn(['mp_preference_id', 'mp_payment_id']);
        });
    }

    public function down(): void
    {
        Schema::table('_vendas', function (Blueprint $table) {
            $table->renameColumn('status_pagamento', 'mp_status');
            $table->string('mp_preference_id')->nullable();
            $table->string('mp_payment_id')->nullable();
        });
    }
};
