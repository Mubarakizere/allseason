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
            if (!Schema::hasColumn('orders', 'weflexfy_request_token')) {
                $table->string('weflexfy_request_token')->nullable()->after('session_id');
            }
        });

        Schema::table('room_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('room_bookings', 'weflexfy_request_token')) {
                $table->string('weflexfy_request_token')->nullable()->after('stripe_session_id');
            }
        });

        Schema::table('venue_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('venue_bookings', 'weflexfy_request_token')) {
                $table->string('weflexfy_request_token')->nullable()->after('stripe_session_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'weflexfy_request_token')) {
                $table->dropColumn('weflexfy_request_token');
            }
        });

        Schema::table('room_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('room_bookings', 'weflexfy_request_token')) {
                $table->dropColumn('weflexfy_request_token');
            }
        });

        Schema::table('venue_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('venue_bookings', 'weflexfy_request_token')) {
                $table->dropColumn('weflexfy_request_token');
            }
        });
    }
};
