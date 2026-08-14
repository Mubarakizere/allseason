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
        Schema::create('venue_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade');
            $table->foreignId('venue_package_id')->constrained('venue_packages')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->date('booking_date');
            $table->decimal('total_price', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->string('payment_status')->default('unpaid'); // unpaid, deposit_paid, fully_paid
            $table->string('status')->default('pending'); // pending, confirmed, cancelled
            $table->string('stripe_session_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_bookings');
    }
};
