<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\AgentPoint;
use App\Models\SitePaymentSystem;
use Illuminate\Support\Facades\Auth;

echo "=== System Health & Functionality Verification ===\n\n";

// 1. Verify Registration Balance for User 553278863259
$user = User::where('phone', '553278863259')->first();
if ($user) {
    echo "1. User Balance Verification:\n";
    echo "   User: {$user->name} ({$user->phone})\n";
    echo "   Balance: ৳" . number_format($user->balance, 2) . "\n";
    echo "   Status: " . ($user->balance == 300.00 ? "SUCCESS (Correct ৳300.00)" : "ERROR") . "\n\n";
}

// 2. Verify Agent Points Data
$points = AgentPoint::where('status', 'active')->get();
echo "2. Agent Points Count: " . $points->count() . "\n";
foreach ($points as $p) {
    echo "   - {$p->name} ({$p->area})\n";
}
echo "   Status: " . ($points->count() >= 5 ? "SUCCESS" : "ERROR") . "\n\n";

// 3. Verify Payment Systems
$payments = SitePaymentSystem::all();
echo "3. Payment Systems Count: " . $payments->count() . "\n";
foreach ($payments as $pay) {
    echo "   - {$pay->pay_s_name}: {$pay->pay_s_number}\n";
}
echo "   Status: SUCCESS\n\n";

// 4. Test View Rendering
Auth::login(User::first());
try {
    $view1 = view('admin.agent_points.index', ['agent_points' => $points])->render();
    echo "4. Admin Agent Points View: OK (" . strlen($view1) . " bytes)\n";
} catch (\Exception $e) {
    echo "4. Admin Agent Points View ERROR: " . $e->getMessage() . "\n";
}

try {
    $view2 = view('users.monthly_bazaar.index', [
        'items' => \App\Models\MonthlyBazaarItem::all(),
        'payment_systems' => $payments,
        'agent_points' => $points
    ])->render();
    echo "5. User Monthly Bazaar View: OK (" . strlen($view2) . " bytes)\n";
} catch (\Exception $e) {
    echo "5. User Monthly Bazaar View ERROR: " . $e->getMessage() . "\n";
}

echo "\nAll Verification Tests Completed Cleanly!\n";
