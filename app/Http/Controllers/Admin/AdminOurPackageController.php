<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OurPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AdminOurPackageController extends Controller
{
    /**
     * Display a listing of package slider images.
     */
    public function index()
    {
        $packages = OurPackage::latest()->get();
        return view('admin.our_package.index', compact('packages'));
    }

    /**
     * Store a newly created package slider image.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $uploadDir = public_path('upload/our_packages');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageFile = $request->file('image');
        $imgName = time() . '_' . Str::random(6) . '.' . $imageFile->getClientOriginalExtension();

        if (class_exists('Intervention\Image\Facades\Image')) {
            try {
                Image::make($imageFile)->save($uploadDir . '/' . $imgName);
            } catch (\Exception $e) {
                $imageFile->move($uploadDir, $imgName);
            }
        } else {
            $imageFile->move($uploadDir, $imgName);
        }

        OurPackage::create([
            'image' => 'upload/our_packages/' . $imgName,
            'status' => 1,
        ]);

        $notification = [
            'message' => 'Our Package image uploaded successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy($id)
    {
        $package = OurPackage::findOrFail($id);

        if (!empty($package->image) && file_exists(public_path($package->image))) {
            @unlink(public_path($package->image));
        }

        $package->delete();

        $notification = [
            'message' => 'Our Package image deleted successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
