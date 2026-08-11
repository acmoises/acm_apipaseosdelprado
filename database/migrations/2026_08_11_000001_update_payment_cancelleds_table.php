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
        Schema::table('payment_cancelleds', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_cancelleds', 'resident_id')) {
                $table->unsignedBigInteger('resident_id')->nullable()->after('payment_id');
            }
            if (!Schema::hasColumn('payment_cancelleds', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('resident_id');
            }
            if (!Schema::hasColumn('payment_cancelleds', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable()->after('payment_type');
            }
            if (!Schema::hasColumn('payment_cancelleds', 'amount')) {
                $table->decimal('amount', 10, 2)->default(0)->after('service_id');
            }
            if (!Schema::hasColumn('payment_cancelleds', 'payment_identifier')) {
                $table->string('payment_identifier')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('payment_cancelleds', 'reason')) {
                $table->text('reason')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_cancelleds', function (Blueprint $table) {
            $table->dropColumn([
                'resident_id',
                'payment_type',
                'service_id',
                'amount',
                'payment_identifier',
                'reason',
            ]);
        });
    }
};
