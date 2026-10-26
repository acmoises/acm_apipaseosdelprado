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
        Schema::table('system_payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->nullable()->after('year');
            $table->string('notes')->nullable()->after('amount');
            $table->unique(['year', 'month'], 'system_payments_year_month_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_payments', function (Blueprint $table) {
            $table->dropUnique('system_payments_year_month_unique');
            $table->dropColumn(['amount', 'notes']);
        });
    }
};
