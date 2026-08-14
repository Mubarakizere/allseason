<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::table('orders', function (Blueprint $table) {
        $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
    });
    echo "Added ['user_id'] to 'users' successfully.\n";
} catch (\Exception $e) {
    echo "Failed to add ['user_id']: " . $e->getMessage() . "\n";
}
