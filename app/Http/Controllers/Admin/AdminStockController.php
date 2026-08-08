<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuyStock;
use App\Models\SellStock;
use App\Models\Stock;
use App\Models\StockGallery;
use App\Models\StockPrice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\StockPreset;

class AdminStockController extends Controller
{


    public function index(){
        $presets = StockPreset::orderBy('price', 'asc')->get();
        return view('admin.add-stock', compact('presets'));
    }




    public function allStock()
    {
        $all_stock = Stock::paginate(15);
        return view('admin.all-stock-list',[
            "all_stock" => $all_stock
        ]);
    }



    public function adminStockDetials($id){
        $stock_info = Stock::where('id', $id)->first();
        $images = StockGallery::where('stock_id', $id)->get();
        return view('admin.stock-details',[
            'stock_info' => $stock_info,
            'images' => $images
        ]);
    }


    public function adminStockEdit($id){
        $stock_info = Stock::where('id', $id)->first();
        $images = StockGallery::where('stock_id', $id)->take(6)->get();
        return view('admin.edit-stock',[
            'stock_info' => $stock_info,
            'images' => $images
        ]);
    }




    public function adminStockEditPost(Request $request, $id){

        if ($request->hasFile('stock_images')) {
            foreach ($request->file('stock_images') as $image) {
                $stock_images_name = time().Str::random(5).'.'.$image->getClientOriginalExtension();
                Image::make($image)->save(base_path('public/upload/stock_images/'.$stock_images_name));
                StockGallery::insert([
                    'stock_id' => $id,
                    'image' => $stock_images_name
                ]);
            }
        }




        Stock::where('id', $id)->update([
            'stock_name' => $request->stock_name,
            'stock_quantity'=> $request->stock_quantity,
            'description'=>$request->stock_description
        ]);


        return redirect('/admin/stock/allStock');


    }





    public function store(Request $request)
    {
        $request->validate([
            'stock_name' => ['required'],
            'selling_price' => ['required'],
            'buying_price' => ['required'],
            'stock_quantity' => ['required'],
        ], [
            'stock_name.required' => 'Stock Name is required',
            'selling_price.required' => 'Selling Price is required',
            'buying_price.required' => 'Buying Price is required',
            'stock_quantity.required' => 'Quantity is required',
        ]);


        // Start a new database transaction
        // DB::beginTransaction();

        // try {
            // Create a new Stock model
            $stock = new Stock;
            $stock->stock_name = $request->stock_name;
            $stock->stock_quantity = $request->stock_quantity;
            $stock->description = $request->stock_description;
            $stock->published_date = Carbon::now();
            $stock->save();

            // Save the stock images to the database
            if ($request->hasFile('stock_images')) {
                foreach ($request->file('stock_images') as $image) {
                    $stock_images_name = time() . Str::random(5) . '.' . $image->getClientOriginalExtension();
                    Image::make($image)->save(base_path('public/upload/stock_images/' . $stock_images_name));
                    $stockImage = new StockGallery;
                    $stockImage->stock_id = $stock->id;
                    $stockImage->image = $stock_images_name;
                    $stockImage->save();
                }
            }

            // Create a new StockPrice model
            $StockPrice = new StockPrice;
            $StockPrice->stock_id = $stock->id;
            $StockPrice->buying_price = $request->buying_price;
            $StockPrice->selling_price = $request->selling_price;
            $StockPrice->pricing_date = Carbon::now();
            $StockPrice->save();

            // Commit the changes to the database
            DB::commit();

            // Redirect the user to a success page or show a success message
            return redirect('/admin/stock/allStock');

        // } catch (\Exception $e) {
        //     // If an error occurs, rollback the transaction and show an error message
        //     DB::rollback();
        //     return redirect()->back()->with('error', 'Error adding stock: ' . $e->getMessage());
        // }

    }








    public function list(){
        $stock_pricing_info = Stock::paginate(15);
        return view('admin.all-stock-picing-info',[
            'all_stock' => $stock_pricing_info
        ]);
    }



    public function pricing($id)
    {
        return view('admin.pricing-stock',[
            'id'=>$id
        ]);
    }


    public function pricingPost(Request $request){
        $request->validate([
            'selling_price' => ['required'],
            'buying_price' => ['required'],
        ], [
            'selling_price.required' => 'Selling Price is required',
            'buying_price.required' => 'Buying Price is required',
        ]);

        StockPrice::insert([
            'stock_id' => $request->stock_id,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price,
            'pricing_date' => Carbon::now(),
        ]);

        return back()->with('success', "Price Add Successfully");
    }












    // Buyyyyyyyyyyyyyyyyy
    public function ByuList(){
        $all_buy_list = BuyStock::where("status",'aproved')->get();
        return view('admin.all-buy-list',[
            "all_buy_list" => $all_buy_list
        ]);
    }
    public function ByuRequestList(){
        $all_buy_request = BuyStock::where("status",'pending')->get();
        return view('admin.request-for-buy',[
            'all_buy_request' => $all_buy_request
        ]);
    }

    public function ByuRequestAproved($id){
        $update_info = BuyStock::where("id", $id)->first();

        if ($update_info && $update_info->status !== 'aproved') {
            BuyStock::where("id", $id)->update([
                "status" => "aproved"
            ]);
            $stock_info = Stock::where('id', $update_info->stock_id)->first();

            if ($stock_info) {
                Stock::where('id', $update_info->stock_id)->update([
                    'stock_quantity' => max(0, $stock_info->stock_quantity - $update_info->buy_quantiy)
                ]);
            }

            // Log in Wallet Transaction Ledger
            RecordWalletLedger(
                $update_info->user_id,
                'Stock Purchase',
                0,
                $update_info->buyed_price,
                'Wallet Balance',
                $update_info->trx_number
            );
        }

        return back();

    }

    public function ByuRequestrejected($id){
        BuyStock::where("id", $id)->update([
            "status" => "rejected"
        ]);
        return back();
    }







    // Sellllllllllllllll
    public function SellList(){
        $all_sell_list = SellStock::where("status",'aproved')->get();
        return view('admin.all-sell-list',[
            "all_sell_list" => $all_sell_list
        ]);
    }
    public function SellRequestList(){
        $all_sell_request = SellStock::where("status",'pending')->get();
        return view('admin.request-for-sell',[
            'all_sell_request' => $all_sell_request
        ]);
    }

    public function SellRequestAproved($id){
        SellStock::where("id", $id)->update([
            "status" => "aproved"
        ]);
        //
        $update_info = SellStock::where("id", $id)->first();
        // $buy_stock_info = BuyStock::where("id", $update_info->buy_id)->first();
        BuyStock::where("id", $update_info->buy_id)->update([
            "status" => "sellaproved"
        ]);

        $total_sell_price = $update_info->selled_price * $update_info->selled_quantiy;

        $user_info = User::where('id', $update_info->user_id)->first();

        User::where('id', $update_info->user_id)->update([
            'balance' => $user_info->balance + $total_sell_price
        ]);

        return back();
    }
    public function SellRequestRejected($id){
        SellStock::where("id", $id)->update([
            "status" => "rejected"
        ]);
        return back();
    }




















    public function destroy($id)
    {
        return $this->stockDelete($id);
    }

    public function stockDelete($id)
    {
        $stock = Stock::find($id);

        if ($stock) {
            $stockImages = StockGallery::where('stock_id', $id)->get();
            foreach ($stockImages as $stockImage) {
                $path = base_path('public/upload/stock_images/' . $stockImage->image);
                if (file_exists($path)) {
                    @unlink($path);
                }
                $stockImage->delete();
            }

            StockPrice::where('stock_id', $id)->delete();
            $stock->delete();
        }

        return back()->with('success', 'Stock Deleted Successfully');
    }

    public function imageDelete($id){
        $stockImages = StockGallery::where('id', $id)->delete();
        return back();
    }
}
