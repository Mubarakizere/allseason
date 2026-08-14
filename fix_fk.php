<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
    });
    echo "Dropped ['user_id'] successfully.\n";
} catch (\Exception $e) {
    echo "Failed to drop ['user_id']: " . $e->getMessage() . "\n";
}
