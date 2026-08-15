<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payrolls')) {
            Schema::create('payrolls', function (Blueprint $table) {
                $table->id();
                $table->string('employee_name');
                $table->string('employee_type')->default('Waiter'); // Waiter, Chef, Manager, Driver, Security, Cleaning, Other
                $table->string('month'); // e.g. August 2026
                $table->decimal('base_salary', 12, 2)->default(0);
                $table->decimal('bonuses', 12, 2)->default(0);
                $table->decimal('deductions', 12, 2)->default(0);
                $table->decimal('net_salary', 12, 2)->default(0);
                $table->enum('status', ['paid', 'pending'])->default('pending');
                $table->string('payment_method')->default('Cash'); // Cash, Bank Transfer, Mobile Money
                $table->date('payment_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
