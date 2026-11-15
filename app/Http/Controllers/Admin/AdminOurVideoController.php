<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OurVideo;
use Illuminate\Http\Request;

class AdminOurVideoController extends Controller
{
    /**
     * Display a listing of all videos.
     */
    public function index()
    {
        $videos = OurVideo::orderBy('order_num', 'asc')->latest()->paginate(15);
        return view('admin.our_videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        return view('admin.our_videos.create');
    }

    /**
     * Store a newly created video in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'video_url' => 'required|string|max:1000',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:1000',
            'order_num' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['title'] = $validated['title'] ?: ('ভিডিও #' . ((OurVideo::max('id') ?? 0) + 1));
        $validated['order_num'] = $request->input('order_num', 0);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        OurVideo::create($validated);

        return redirect()->route('admin.our_videos.index')->with('success', 'ভিডিওটি সফলভাবে যুক্ত করা হয়েছে!');
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit($id)
    {
        $video = OurVideo::findOrFail($id);
        return view('admin.our_videos.edit', compact('video'));
    }

    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, $id)
    {
        $video = OurVideo::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'video_url' => 'required|string|max:1000',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:1000',
            'order_num' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['title'] = $validated['title'] ?: ($video->title ?: ('ভিডিও #' . $video->id));
        $validated['order_num'] = $request->input('order_num', 0);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $video->update($validated);

        return redirect()->route('admin.our_videos.index')->with('success', 'ভিডিও তথ্য সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Remove the specified video from storage.
     */
    public function destroy($id)
    {
        $video = OurVideo::findOrFail($id);
        $video->delete();

        return redirect()->route('admin.our_videos.index')->with('success', 'ভিডিওটি সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * Toggle the active status of a video.
     */
    public function toggleStatus($id)
    {
        $video = OurVideo::findOrFail($id);
        $video->is_active = !$video->is_active;
        $video->save();

        return back()->with('success', 'স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!');
    }
}
