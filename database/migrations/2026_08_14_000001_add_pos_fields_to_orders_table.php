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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->nullable()->default(0)->after('total_price');
            $table->decimal('amount_tendered', 10, 2)->nullable()->after('payment_method');
            $table->decimal('change_due', 10, 2)->nullable()->after('amount_tendered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'amount_tendered', 'change_due']);
        });
    }
};
