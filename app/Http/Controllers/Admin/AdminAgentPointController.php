<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use Illuminate\Http\Request;

class AdminAgentPointController extends Controller
{
    public function index()
    {
        $agent_points = AgentPoint::latest()->get();
        return view('admin.agent_points.index', compact('agent_points'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->id) {
            $point = AgentPoint::findOrFail($request->id);
            $point->update($request->only(['name', 'area', 'address', 'contact_number', 'status']));
            $msg = 'সংগ্রহ এজেন্ট পয়েন্ট সফলভাবে আপডেট করা হয়েছে!';
        } else {
            AgentPoint::create($request->only(['name', 'area', 'address', 'contact_number', 'status']));
            $msg = 'নতুন সংগ্রহ এজেন্ট পয়েন্ট সফলভাবে যুক্ত করা হয়েছে!';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function destroy($id)
    {
        $point = AgentPoint::findOrFail($id);
        $point->delete();
        return redirect()->back()->with('success', 'সংগ্রহ এজেন্ট পয়েন্ট সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
