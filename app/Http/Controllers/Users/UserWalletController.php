<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWalletController extends Controller
{
    public function ledger()
    {
        $transactions = WalletTransaction::where('user_id', Auth::id())->latest()->get();
        $user_card = GetUserCardNumber(Auth::id());
        return view('users.wallet.ledger', compact('transactions', 'user_card'));
    }
}
