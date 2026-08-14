<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
 
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'billing_address_id')) {
                // Drop FK first, then column
                $table->dropForeign(['billing_address_id']);
                $table->dropColumn('billing_address_id');
            }
        });

 
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('label', 255)->default('delivery')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ----- ORDERS: re-add billing_address_id -----
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'billing_address_id')) {
                $table->unsignedBigInteger('billing_address_id')->nullable()->after('delivery_address_id');
                $table->foreign('billing_address_id')
                      ->references('id')
                      ->on('addresses')
                      ->nullOnDelete();
            }
        });

         Schema::table('addresses', function (Blueprint $table) {
            $table->enum('label', ['delivery','billing'])->default('delivery')->change();
        });
    }
};
