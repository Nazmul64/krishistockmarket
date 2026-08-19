<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:5120',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('upload/features');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $file = $request->file('image');
            $fileName = 'feature_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $imagePath = 'upload/features/' . $fileName;
        }

        Feature::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'icon' => $request->icon ?? 'fa-check-circle',
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
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:5120',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
        ]);

        $feature = Feature::findOrFail($id);
        $imagePath = $feature->image;

        if ($request->hasFile('image')) {
            $uploadDir = public_path('upload/features');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Delete old image if exists
            if (!empty($feature->image) && file_exists(public_path($feature->image))) {
                @unlink(public_path($feature->image));
            }

            $file = $request->file('image');
            $fileName = 'feature_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $imagePath = 'upload/features/' . $fileName;
        }

        $feature->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'icon' => $request->icon ?? $feature->icon ?? 'fa-check-circle',
            'color' => $request->color ?? $feature->color ?? '#1b88ce',
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

        if (!empty($feature->image) && file_exists(public_path($feature->image))) {
            @unlink(public_path($feature->image));
        }

        $feature->delete();

        $notification = [
            'message' => 'Feature Box Deleted Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}

