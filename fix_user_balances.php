<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\WalletTransaction;

echo "--- Fixing Double-Credited Registration Balances ---\n";

$users = User::where('role', 'user')->get();

foreach ($users as $user) {
    // Check if user has only 1 Registration Balance ledger entry and 0 deposits/purchases
    $txns = WalletTransaction::where('user_id', $user->id)->get();
    $regTxn = $txns->where('transaction_type', 'Registration Balance')->first();

    if ($regTxn && $txns->count() == 1) {
        $expectedBalance = (float) $regTxn->credit_amount;
        if ((float) $user->balance != $expectedBalance) {
            echo "Correcting User ID {$user->id} ({$user->username}): Old Balance = ৳{$user->balance} -> New Balance = ৳{$expectedBalance}\n";
            $user->update(['balance' => $expectedBalance]);
            $regTxn->update([
                'previous_balance' => 0.00,
                'new_balance' => $expectedBalance
            ]);
        }
    }
}

echo "Done!\n";
