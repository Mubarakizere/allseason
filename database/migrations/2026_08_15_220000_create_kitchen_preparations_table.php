<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kitchen_preparations')) {
            Schema::create('kitchen_preparations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('menu_id')->nullable()->constrained()->nullOnDelete();
                $table->string('item_name');
                $table->decimal('quantity_prepared', 10, 2)->default(1);
                $table->string('prepared_by')->default('Kitchen Staff');
                $table->enum('status', ['in_preparation', 'completed', 'cancelled'])->default('completed');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kitchen_preparations');
    }
};
