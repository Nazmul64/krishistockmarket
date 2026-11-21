<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use Illuminate\Http\Request;

class AdminAgentPointController extends Controller
{
    public function index()
    {
        $agent_points = AgentPoint::withCount('marketRequests')->latest()->get();
        return view('admin.agent_points.index', compact('agent_points'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'agent_name' => 'nullable|string|max:255',
            'area' => 'required|string|max:255',
            'district' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'agent_name', 'area', 'district', 'address', 'contact_number', 'capacity', 'status']);

        if ($request->id) {
            $point = AgentPoint::findOrFail($request->id);
            $point->update($data);
            $msg = 'সংগ্রহ এজেন্ট পয়েন্ট সফলভাবে আপডেট করা হয়েছে!';
        } else {
            $count = AgentPoint::count();
            $data['code'] = 'AGP-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            $point = AgentPoint::create($data);
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
