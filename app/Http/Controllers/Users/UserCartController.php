<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\BuyStock;
use App\Models\Stock;
use App\Models\UserCart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
class UserCartController extends Controller
{



    public function index(){
        $user_cart_list = UserCart::where('user_id', Auth::user()->id)->get();

        // $check_quantity =Stock::whereIn('id', $user_cart_list->stock_id)->get();

        return view('users.user-cart',[
            'user_cart_list' => $user_cart_list
        ]);
    }





    public function AddCart($id){

        $stock_info = Stock::find($id);
        $available_stock = "0";
        $available_stock = $stock_info->stock_quantity - $stock_info->sold_quantity;
        $input_quantity = "1";



        if ($available_stock < $input_quantity ) {
            return back()->with('stockout', 'Stock Out');
        }

        if (UserCart::where('stock_id', $id)->where('user_id', auth()->id())->exists()) {

            $available_stock_on_cartlist = UserCart::where('stock_id', $id)->where('user_id', auth()->id())->first()->quantity;

            if ($available_stock < $input_quantity + $available_stock_on_cartlist ) {
                return redirect()->route('userbuystocklist')->with('already_in', 'Available for your cart');
            }else{
                UserCart::where('stock_id', $id)->where('user_id', auth()->id())->increment('quantity', $input_quantity);
            }

        }
        else{
            UserCart::insert([
                'user_id' => auth()->id(),
                'stock_id' => $id,
                'add_date' => Carbon::now(),
                'quantity' => $input_quantity,
                'created_at' => Carbon::now(),
            ]);
        }

        return $this->index();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CardUpdate(Request $request)
    {
        // $number = $request->product_quantity;

        // foreach ($number as $key => $product_quantity_number) {
        //     $product_id = Cart::where('id', $key)->first()->product_id;
        //     $available_stock = Product::where('id', $product_id)->first()->product_quantity;
        //     //Already have Quantity
        //     $already_have_quantity = Cart::where('id', $key)->first()->quantity;
        //     //Increase Quantity
        //     $increase_quantity = $product_quantity_number - $already_have_quantity ;


        //     if ($available_stock == 0) {
        //         return redirect('cart')->with('stock_out', ' available stock 0');
        //     }else{
        //         if ($available_stock < $already_have_quantity+$increase_quantity) {
        //             return redirect('cart')->with('stock_out', 'available stock is low');
        //         }else{
        //             Cart::find($key)->update([
        //                 'quantity' => $product_quantity_number,
        //             ]);

        //         }
        //     }

        // }
        // return  back();
    }

    // public function clearshoppingcart() {
    //     UserCart::where('user_id', auth()->id())->delete();
    //     return back();
    // }




    public function removeCartItem($id) {
        UserCart::where('id', $id)->where('user_id', auth()->id())->delete();
        return back();
    }



    public function CartPost(Request $request){


        $request->validate([
            'payment_system_id' => ['required'],
            'from_phone_number' => ['required'],
            'trx_id' => ['required'],
            'sceenshorts' => ['required'],
        ], [
            'payment_system_id.required' => 'Please Select Pyment System',
            'from_phone_number.required' => 'Phone Number is required',
            'trx_id.required' => 'Trx.ID is required',
            'sceenshorts.required' => 'Sceenshort is required',
        ]);

        // Get the form data
        $payment_system_id = request('payment_system_id');
        $from_phone_number = request('from_phone_number');
        $trx_id = request('trx_id');

        if ($request->hasFile('sceenshorts')) {
            $trnx_image_name = time().Str::random(5).'.'.$request->file('sceenshorts')->getClientOriginalExtension();
            Image::make($request->file('sceenshorts'))->save(base_path('public/upload/payment/'.$trnx_image_name));
        }else{
            $trnx_image_name = "";
        }

        $productRows = $request->input('product_row');


        // Loop through each product and create a new order item instance
        foreach ($productRows as $key => $product) {
            $stockId = $product['stock_id'];
            $cart_id = $product['cart_id'];
            $quantity = $product['quantity'];
            $price_per_unit = $product['price_per_unit'];

            $total_price = $price_per_unit * $quantity;


            $stock_info = Stock::where('id', $product['stock_id'])->first();
            $available_quantity = $stock_info->stock_quantity - $stock_info->sold_quantity;

            if ($available_quantity < $quantity) {
                return back()->with('stock_out', $stock_info->stock_name." Product Stock Out Please removed this");
            }

            BuyStock::insert([
                "user_id" => Auth::user()->id,
                "payment_id" => $payment_system_id,
                "pay_from_number" => $from_phone_number,
                "trx_number" => $trx_id,
                "sceenshort" => $trnx_image_name,
                "buy_quantiy" => $quantity,
                "stock_id" => $stockId,
                "buyed_price" => $total_price,
                "status" => "pending",
                "created_at" => Carbon::now(),
            ]);
            UserCart::where('id', $cart_id)->where('user_id', auth()->id())->delete();
            Stock::where('id', $stockId)->update([
                'sold_quantity' => $stock_info->sold_quantity + $product['quantity'],
                'stock_quantity' => $stock_info->stock_quantity - $product['quantity'],
            ]);

        }

        return redirect()->route('userbuystocklist');


    }


}
