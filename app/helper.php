<?php

use App\Models\Admin\SiteSetting;
use App\Models\SitePaymentSystem;
use App\Models\Stock;
use App\Models\StockGallery;
use App\Models\StockPrice;
use App\Models\User;
use App\Models\UserPaymentSystem;
use Illuminate\Support\Facades\DB;

if (!function_exists('setting')) {
    function setting($value)
    {
        $setting = SiteSetting::where('name', $value)->first();
        if( $setting ){
            return $setting->value;
        }else{
            return false;
        }
    }
}


function StockAllPricing($id){
    $all_pricing = StockPrice::where('stock_id', $id)->orderBy('pricing_date','DESC')->paginate(15);
    return $all_pricing;
}

function StockLastPricing($id){
    $last_pricing = StockPrice::where('stock_id', $id)->orderBy('pricing_date', 'DESC')->first();
    return $last_pricing;
}

function sellingPriceForChart($stock_id){
    $history = stockFullPriceHistory($stock_id);
    return $history['sellingPrices'];
}


function buyingPriceForChart($stock_id){
    $history = stockFullPriceHistory($stock_id);
    return $history['buyingPrices'];
}


function stockFullPriceHistory($stock_id) {
    $history = StockPrice::where('stock_id', $stock_id)
        ->orderBy('pricing_date', 'ASC')
        ->get(['pricing_date', 'selling_price', 'buying_price']);

    $dates = [];
    $sellingPrices = [];
    $buyingPrices = [];

    foreach ($history as $row) {
        $dates[] = \Carbon\Carbon::parse($row->pricing_date)->format('d M');
        $sellingPrices[] = (float) $row->selling_price;
        $buyingPrices[] = (float) $row->buying_price;
    }

    // Fallback if no historical price points exist: use current price point from StockLastPrice
    if (empty($dates)) {
        $lastPriceRecord = StockLastPrice($stock_id);
        if ($lastPriceRecord) {
            $dates[] = \Carbon\Carbon::parse($lastPriceRecord->pricing_date ?? now())->format('d M');
            $sellingPrices[] = (float) $lastPriceRecord->selling_price;
            $buyingPrices[] = (float) $lastPriceRecord->buying_price;
        }
    }

    return [
        'dates' => $dates,
        'sellingPrices' => $sellingPrices,
        'buyingPrices' => $buyingPrices,
    ];
}


// ========= All single Info
function getFirstImages($id){
   return StockGallery::where('stock_id', $id)->orderBy('created_at', 'DESC')->first();

//    return $id;
}



function SingleStockInfo($stock_id){
    return Stock::where('id', $stock_id)->first();
}
function SingleUserInfo($user_id){
    return User::where('id', $user_id)->first();
}

function StockLastPrice($stock_id){
   return StockPrice::where('stock_id', $stock_id)->orderBy('pricing_date', 'DESC')->first();
}



// ========= Emploeey Referal id
function ReferalUser($employee_id){
    return User::where('referral_id', $employee_id)->get();
}


function WithdrawMethod($id){
    return UserPaymentSystem::where('id', $id)->first();
}

function SitePaymentSystem($id){
    return SitePaymentSystem::where('id', $id)->first();
}

function GetUserCardNumber($user_id){
    $card = \App\Models\CardNumber::where('used_by', $user_id)->first();
    return $card ? $card->number : 'N/A';
}

function RecordWalletLedger($user_id, $transaction_type, $credit_amount, $debit_amount, $payment_method = 'Wallet Balance', $trx_number = null, $status = 'Approved') {
    $user = \App\Models\User::find($user_id);
    if (!$user) return false;

    $previous_balance = (float) $user->balance;
    $new_balance = $previous_balance + (float) $credit_amount - (float) $debit_amount;

    $user->update(['balance' => $new_balance]);

    $card_number = GetUserCardNumber($user_id);
    $txn_id = 'TXN-' . strtoupper(\Illuminate\Support\Str::random(8));

    return \App\Models\WalletTransaction::create([
        'transaction_id'   => $txn_id,
        'user_id'          => $user_id,
        'card_number'      => $card_number,
        'transaction_type' => $transaction_type,
        'credit_amount'    => $credit_amount,
        'debit_amount'     => $debit_amount,
        'previous_balance' => $previous_balance,
        'new_balance'      => $new_balance,
        'payment_method'   => $payment_method,
        'trx_number'       => $trx_number ?? $txn_id,
        'status'           => $status,
    ]);
}
