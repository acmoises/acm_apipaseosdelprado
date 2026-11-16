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
        Schema::create('billing_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('month')->comment('1 = Enero ... 12 = Diciembre');
            $table->unsignedSmallInteger('year')->comment('Año de aplicación, ej. 2026');
            $table->unsignedTinyInteger('grace_days')->default(5)->comment('Cantidad de días de cobro/prórroga al inicio del mes');
            $table->string('notes', 255)->nullable()->comment('Nota o motivo del ajuste');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['year', 'month'], 'billing_configs_year_month_unique');
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_configs');
    }
};

