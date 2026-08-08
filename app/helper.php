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
    $sell_ing_price_data = StockPrice::where('stock_id', $stock_id)->orderBy('pricing_date', 'ASC')->take(10)->get('selling_price');
    $data = $sell_ing_price_data->toArray();
    $prices = array_map(function($item) {
        return (float) $item['selling_price'];
    }, $data);

    if (empty($prices)) {
        return [0, 0];
    }
    if (count($prices) === 1) {
        return [$prices[0], $prices[0]];
    }

    return $prices;
}


function buyingPriceForChart($stock_id){
    $buying_price_data = StockPrice::where('stock_id', $stock_id)->orderBy('pricing_date', 'ASC')->take(10)->get('buying_price');
    $data = $buying_price_data->toArray();
    $prices = array_map(function($item) {
        return (float) $item['buying_price'];
    }, $data);

    if (empty($prices)) {
        return [0, 0];
    }
    if (count($prices) === 1) {
        return [$prices[0], $prices[0]];
    }

    return $prices;
}


function stockFullPriceHistory($stock_id) {
    $history = StockPrice::where('stock_id', $stock_id)
        ->orderBy('pricing_date', 'ASC')
        ->get(['pricing_date', 'selling_price', 'buying_price']);

    $dates = [];
    $sellingPrices = [];
    $buyingPrices = [];

    foreach ($history as $row) {
        $dates[] = \Carbon\Carbon::parse($row->pricing_date)->format('d M Y');
        $sellingPrices[] = (float) $row->selling_price;
        $buyingPrices[] = (float) $row->buying_price;
    }

    if (empty($dates)) {
        $dates = [\Carbon\Carbon::now()->format('d M Y')];
        $sellingPrices = [0];
        $buyingPrices = [0];
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
