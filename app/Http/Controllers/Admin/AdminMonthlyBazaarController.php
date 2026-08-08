<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyBazaarItem;
use App\Models\MonthlyBazaarOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class AdminMonthlyBazaarController extends Controller
{
    public function index()
    {
        $items = MonthlyBazaarItem::latest()->get();
        return view('admin.monthly_bazaar.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'package_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . Str::random(5) . '.' . $request->file('image')->getClientOriginalExtension();
            Image::make($request->file('image'))->save(base_path('public/upload/monthly_bazaar/' . $imageName));
        }

        MonthlyBazaarItem::create([
            'title' => $request->title,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'image' => $imageName,
            'status' => 'active',
        ]);

        $notification = [
            'message' => 'Monthly Grocery Package Created Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function edit($id)
    {
        $item = MonthlyBazaarItem::findOrFail($id);
        return view('admin.monthly_bazaar.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'package_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $item = MonthlyBazaarItem::findOrFail($id);
        $imageName = $item->image;

        if ($request->hasFile('image')) {
            if ($item->image && file_exists(public_path('upload/monthly_bazaar/' . $item->image))) {
                @unlink(public_path('upload/monthly_bazaar/' . $item->image));
            }
            $imageName = time() . Str::random(5) . '.' . $request->file('image')->getClientOriginalExtension();
            Image::make($request->file('image'))->save(base_path('public/upload/monthly_bazaar/' . $imageName));
        }

        $item->update([
            'title' => $request->title,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        $notification = [
            'message' => 'Monthly Grocery Package Updated Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.monthly_bazaar.index')->with($notification);
    }

    public function destroy($id)
    {
        $item = MonthlyBazaarItem::findOrFail($id);
        if ($item->image && file_exists(public_path('upload/monthly_bazaar/' . $item->image))) {
            @unlink(public_path('upload/monthly_bazaar/' . $item->image));
        }
        $item->delete();

        $notification = [
            'message' => 'Monthly Grocery Package Deleted Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // Orders Requests Management
    public function orders()
    {
        $orders = MonthlyBazaarOrder::with(['user', 'item'])->latest()->get();
        return view('admin.monthly_bazaar.orders', compact('orders'));
    }

    public function approveOrder($id)
    {
        $order = MonthlyBazaarOrder::findOrFail($id);
        $order->update(['status' => 'approved']);

        // Update item sold and stock quantity
        $item = MonthlyBazaarItem::find($order->item_id);
        if ($item) {
            $item->increment('sold_quantity', $order->quantity);
            $item->decrement('quantity', $order->quantity);
        }

        $notification = [
            'message' => 'Monthly Grocery Order Approved Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function rejectOrder($id)
    {
        $order = MonthlyBazaarOrder::findOrFail($id);
        $order->update(['status' => 'rejected']);

        $notification = [
            'message' => 'Monthly Grocery Order Rejected!',
            'alert-type' => 'warning'
        ];

        return redirect()->back()->with($notification);
    }
}
