<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Stock;

try {
    $stock = Stock::first();
    if ($stock) {
        $stock->is_unlimited = 1;
        $stock->save();
        echo "Successfully set stock 1 is_unlimited = 1\n";
    }
    
    // Compile views
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "View cache cleared cleanly.\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
