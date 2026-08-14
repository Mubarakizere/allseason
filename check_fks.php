<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fks = \Illuminate\Support\Facades\DB::select("PRAGMA foreign_key_list('orders')");
print_r($fks);
