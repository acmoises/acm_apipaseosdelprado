<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Permitir valores nulos en las columnas
            $table->string('maternal_surname')->nullable()->change();
            $table->unsignedBigInteger('phone_number')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('card_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Restaurar las columnas a no nulas
            $table->string('maternal_surname')->nullable(false)->change();
            $table->unsignedBigInteger('phone_number')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('card_id')->nullable(false)->change();
        });
    }
};
