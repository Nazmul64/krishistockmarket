<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    /**
     * Display a listing of the gallery images.
     */
    public function index()
    {
        $galleries = Gallery::orderBy('sort_order', 'asc')->latest()->paginate(24);
        $totalPhotos = Gallery::count();
        $activePhotos = Gallery::where('status', 1)->count();
        $inactivePhotos = Gallery::where('status', 0)->count();

        return view('admin.gallery.index', compact('galleries', 'totalPhotos', 'activePhotos', 'inactivePhotos'));
    }

    /**
     * Store newly created gallery image(s) in storage.
     * Supports both single and multiple image upload.
     */
    public function store(Request $request)
    {
        $request->validate([
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'title_en' => 'nullable|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'status' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        $uploadPath = public_path('upload/gallery');
        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('image')) {
            $files = [$request->file('image')];
        }

        if (empty($files)) {
            return back()->with('error', 'অনুগ্রহ করে অন্তত একটি ছবি নির্বাচন করুন (Please select at least one image).');
        }

        $uploadedCount = 0;
        $status = ($request->has('status') && in_array($request->status, ['1', 'on', 1, true], true)) ? 1 : 1;
        $baseSortOrder = (int) ($request->sort_order ?? 0);

        foreach ($files as $index => $file) {
            if ($file && $file->isValid()) {
                $ext = $file->getClientOriginalExtension();
                $filename = 'gallery_' . time() . '_' . Str::random(8) . '.' . $ext;

                try {
                    // Try Intervention Image resizing / optimization
                    $img = Image::make($file->getRealPath());
                    $img->orientate();
                    $img->save($uploadPath . '/' . $filename, 88);
                } catch (\Exception $e) {
                    // Fallback to basic file move if intervention fails
                    $file->move($uploadPath, $filename);
                }

                Gallery::create([
                    'title_en' => $request->title_en ?: null,
                    'title_bn' => $request->title_bn ?: null,
                    'image' => $filename,
                    'status' => $status,
                    'sort_order' => $baseSortOrder + $index,
                ]);

                $uploadedCount++;
            }
        }

        return back()->with('success', "{$uploadedCount}টি ছবি সফলভাবে গ্যালারিতে আপলোড করা হয়েছে!");
    }

    /**
     * Toggle status of a gallery item.
     */
    public function status($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->status = $gallery->status == 1 ? 0 : 1;
        $gallery->save();

        return response()->json([
            'success' => true,
            'status' => $gallery->status,
            'message' => 'গ্যালারি ছবির স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!'
        ]);
    }

    /**
     * Remove the specified gallery item from storage.
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $filePath = public_path('upload/gallery/' . $gallery->image);
        if (File::exists($filePath)) {
            @unlink($filePath);
        }
        $gallery->delete();

        return back()->with('success', 'গ্যালারির ছবিটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
