<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockPreset;
use Illuminate\Http\Request;

class AdminStockPresetController extends Controller
{
    public function index()
    {
        $presets = StockPreset::orderBy('price', 'asc')->get();
        return view('admin.stock_presets.index', compact('presets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'package_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        StockPreset::create([
            'title' => $request->title,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        $notification = [
            'message' => 'Stock Package Preset Created Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function edit($id)
    {
        $preset = StockPreset::findOrFail($id);
        return view('admin.stock_presets.edit', compact('preset'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'package_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $preset = StockPreset::findOrFail($id);
        $preset->update([
            'title' => $request->title,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        $notification = [
            'message' => 'Stock Package Preset Updated Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.stock_preset.index')->with($notification);
    }

    public function destroy($id)
    {
        $preset = StockPreset::findOrFail($id);
        $preset->delete();

        $notification = [
            'message' => 'Stock Package Preset Deleted Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
