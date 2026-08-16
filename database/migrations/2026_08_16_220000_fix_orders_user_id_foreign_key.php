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
        // 1. Drop legacy foreign key constraint orders_customer_id_foreign if present
        try {
            DB::statement('ALTER TABLE `orders` DROP FOREIGN KEY `orders_customer_id_foreign`');
        } catch (\Throwable $e) {
            // Constraint might already be dropped or not exist
        }

        // 2. Drop orders_user_id_foreign if present to prevent duplication issues
        try {
            DB::statement('ALTER TABLE `orders` DROP FOREIGN KEY `orders_user_id_foreign`');
        } catch (\Throwable $e) {
        }

        // 3. Re-add foreign key pointing user_id to users(id)
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
            }
        });
    }
};
