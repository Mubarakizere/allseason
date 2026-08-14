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
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->decimal('total_price', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->enum('payment_status', ['unpaid', 'deposit_paid', 'fully_paid'])->default('unpaid');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->string('stripe_session_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};
