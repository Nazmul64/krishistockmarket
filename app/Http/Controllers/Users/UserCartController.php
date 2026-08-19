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





    public function AddCart($id, Request $request){
        $stock_info = Stock::find($id);
        if (!$stock_info) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'পণ্যটি পাওয়া যায়নি!'], 404);
            }
            return back()->with('error', 'পণ্যটি পাওয়া যায়নি!');
        }

        $is_unlimited = (bool) $stock_info->is_unlimited;
        $available_stock = $is_unlimited ? 999999 : ($stock_info->stock_quantity - $stock_info->sold_quantity);
        $input_quantity = 1;

        if (!$is_unlimited && $available_stock < $input_quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'দুঃখিত, পণ্যটির পর্যাপ্ত স্টক নেই!'], 400);
            }
            return back()->with('stockout', 'Stock Out');
        }

        $existingCart = UserCart::where('stock_id', $id)->where('user_id', auth()->id())->first();

        if ($existingCart) {
            if (!$is_unlimited && $available_stock < ($input_quantity + $existingCart->quantity)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'আপনার কার্টে ইতিমধ্যে সর্বোচ্চ সংখ্যক স্টক রয়েছে!'], 400);
                }
                return redirect()->route('my.cart')->with('already_in', 'Available for your cart');
            } else {
                $existingCart->increment('quantity', $input_quantity);
            }
        } else {
            UserCart::create([
                'user_id' => auth()->id(),
                'stock_id' => $id,
                'add_date' => Carbon::now(),
                'quantity' => $input_quantity,
                'created_at' => Carbon::now(),
            ]);
        }

        $totalCartCount = UserCart::where('user_id', auth()->id())->sum('quantity');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'পণ্যটি সফলভাবে কার্টে যুক্ত হয়েছে!',
                'cart_count' => (int) $totalCartCount,
                'stock_name' => $stock_info->stock_name
            ]);
        }

        $notification = [
            'message' => 'পণ্যটি কার্টে যুক্ত হয়েছে!',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }

    public function getCartCount(Request $request) {
        $count = UserCart::where('user_id', auth()->id())->sum('quantity');
        return response()->json(['cart_count' => (int) $count]);
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
            'sceenshorts' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
        ], [
            'payment_system_id.required' => 'দয়া করে পেমেন্ট মেথড নির্বাচন করুন',
            'from_phone_number.required' => 'প্রেরকের মোবাইল নম্বর দেওয়া আবশ্যক',
            'trx_id.required' => 'ট্রানজেকশন আইডি (Trx ID) দেওয়া আবশ্যক',
            'sceenshorts.required' => 'পেমেন্টের স্ক্রিনশট আপলোড করা আবশ্যক',
            'sceenshorts.image' => 'স্ক্রিনশট অবশ্যই একটি বৈধ ছবি হতে হবে',
        ]);

        // Get the form data
        $payment_system_id = $request->input('payment_system_id');
        $from_phone_number = $request->input('from_phone_number');
        $trx_id = $request->input('trx_id');

        if ($request->hasFile('sceenshorts')) {
            $image = $request->file('sceenshorts');
            $trnx_image_name = time().'_'.Str::random(8).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('upload/payment');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            Image::make($image)->save($destinationPath.'/'.$trnx_image_name);
        } else {
            $trnx_image_name = "";
        }

        $productRows = $request->input('product_row', []);

        if (empty($productRows)) {
            $notification = [
                'message' => 'আপনার কার্টে কোনো প্রোডাক্ট নেই!',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->with('error', 'আপনার কার্টে কোনো প্রোডাক্ট নেই!');
        }

        // Loop through each product and create a new order item instance
        foreach ($productRows as $key => $product) {
            $stockId = $product['stock_id'];
            $cart_id = $product['cart_id'];
            $quantity = (int) $product['quantity'];
            $price_per_unit = (float) $product['price_per_unit'];
            $total_price = $price_per_unit * $quantity;

            $stock_info = Stock::where('id', $stockId)->first();
            if (!$stock_info) {
                continue;
            }

            $is_unlimited = (bool) $stock_info->is_unlimited;
            $available_quantity = $is_unlimited ? 999999 : ($stock_info->stock_quantity - $stock_info->sold_quantity);

            if (!$is_unlimited && $available_quantity < $quantity) {
                $notification = [
                    'message' => $stock_info->stock_name . " পণ্যটির পর্যাপ্ত স্টক নেই। অনুগ্রহ করে পরিমাণ কমান অথবা রিমুভ করুন।",
                    'alert-type' => 'error'
                ];
                return back()->with($notification)->with('error', $stock_info->stock_name . " পণ্যটির পর্যাপ্ত স্টক নেই।");
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
                "updated_at" => Carbon::now(),
            ]);

            UserCart::where('id', $cart_id)->where('user_id', auth()->id())->delete();

            if (!$is_unlimited) {
                $stock_info->decrement('stock_quantity', $quantity);
            }
            $stock_info->increment('sold_quantity', $quantity);
        }

        $notification = [
            'message' => 'আপনার স্টক ক্রয়ের অনুরোধ সফলভাবে সম্পন্ন হয়েছে! এডমিন যাচাই করে অনুমোদন করবেন।',
            'alert-type' => 'success'
        ];

        return redirect()->route('userbuystocklist')->with($notification)->with('success', 'আপনার স্টক ক্রয়ের অনুরোধ সফলভাবে সম্পন্ন হয়েছে!');
    }


}
