<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class AdminFeatureController extends Controller
{
    public function index()
    {
        $features = Feature::latest()->get();
        return view('admin.feature.index', compact('features'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:100',
        ]);

        Feature::create([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon ?? 'fa-check',
            'color' => $request->color ?? '#1b88ce',
        ]);

        $notification = [
            'message' => 'Feature Box Created Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function edit($id)
    {
        $feature = Feature::findOrFail($id);
        return view('admin.feature.edit', compact('feature'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:100',
        ]);

        $feature = Feature::findOrFail($id);
        $feature->update([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color ?? '#1b88ce',
        ]);

        $notification = [
            'message' => 'Feature Box Updated Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.feature.index')->with($notification);
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();

        $notification = [
            'message' => 'Feature Box Deleted Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
