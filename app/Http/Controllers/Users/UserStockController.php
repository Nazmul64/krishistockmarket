<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\BuyStock;
use App\Models\SellStock;
use App\Models\Stock;
use App\Models\StockGallery;
use App\Models\StockPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $all_stock = Stock::where('status', "active")->get();
        return view('users.stock',[
            'all_stock' => $all_stock
        ]);
    }


    public function userSellStockList(){
        $all_sell_stock = SellStock::where('user_id', Auth::user()->id)->get();
        return view('users.user-sell-stock-list',[
            'all_sell_stock' => $all_sell_stock
        ]);
    }

    public function userBuyStockList(){
        $all_buy_stock = BuyStock::where('user_id', Auth::user()->id)->get();
        return view('users.user-buy-stock-list',[
            'all_buy_stock' => $all_buy_stock
        ]);
    }




    public function SellRequest($id){
        $buy_stock_info = BuyStock::where('id', $id)->first();
        // Update Data
        BuyStock::where('id', $id)->update([
            'status' => "sellpending"
        ]);

        $stock_info  = StockPrice::where('stock_id', $buy_stock_info->stock_id)->orderBy('id', 'desc')->first();

        $sell_price = $stock_info->selling_price * $buy_stock_info->buy_quantiy;
        // Insert Data In Sell Table
        SellStock::insert([
            'user_id' => Auth::user()->id,
            'buy_id' => $id,
            'stock_id' => $buy_stock_info->stock_id,
            'selled_price' => $sell_price,
            'selled_quantiy' => $buy_stock_info->buy_quantiy,
            'selled_date' => Carbon::now(),
            'status' => "pending",
            'created_at' =>  Carbon::now(),
        ]);
        // sellpending
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stock_info = Stock::where('id', $id)->first();
        $images = StockGallery::where('stock_id', $id)->get();
        return view('stock-detials',[
            'stock_info' => $stock_info,
            'images' => $images
        ]);
    }


}
